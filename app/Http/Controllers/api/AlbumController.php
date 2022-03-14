<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Album;
use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AlbumController extends Controller
{
    private function makeJson($state, $data = null, $msg = null)
    {
        return response()->json(['state' => $state, 'data' => $data, 'msg' => $msg])->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }

    public function albumCreate(Request $request)
    {
        try {
            $params = $request->only(['name', 'content']);
            $result = Album::create($params);
            if (!is_null($result)) {
                $id = $result->id;
                if ($request->has('pic')) {
                    $file = $request->pic;
                    $type = $file->extension();
                    $fileName = str_replace('tmp', $type, $file->getFilename());
                    $path = 'public/album/' . $id;
                    $saveResult = Storage::putFileAs($path, $file, $fileName);
                    $url = $path . '/' . $fileName;
                    if (!$saveResult) {
                        return $this->makeJson(0, null, 'COVER_SAVE_ERROR');
                    }
                } else {
                    $url = '/storage/album/albumDefault.png';
                }
                $result->update(['cover' => $url]);
                if ($result) {
                    return $this->makeJson(1, ['id' => $id], null);
                } else {
                    return $this->makeJson(0, null, 'UPDATE_COVER_ERROR');
                }
            } else {
                return $this->makeJson(0, null, 'ALBUM_CREATE_ERROR');
            }
        } catch (\Throwable $th) {
            return $this->makeJson(0, $th, 'ANOTHER_ERROR');
        }
    }

    //修改相簿資訊
    public function albumUpdate(Album $album, Request $request)
    {
        $params = $request->only(['name', 'content']);
        $result = $album->update($params);
        if ($result) {
            return $this->makeJson(1, null, null);
        } else {

            return $this->makeJson(0, null, 'ALBUM_UPDATE_ERROR');
        }
    }

    // 後台顯示所有相簿
    public function albumList(Request $request)
    {
        if ($request->has('limit')) {
            $page = $request->limit; //接收前台傳來的顯示頁面數
        } else {
            $page = 8; //預設為顯示10筆文章,後台文章篇幅小，適合多顯示
        }

        switch ($request->type) { //依據前台要求更改顯示對象
            case 'done':
                $album = Album::where('state', 1); //顯示已完成的公開文章
                break;
            case 'undone':

                $album = Album::where('state', 2); //顯示未完成的草稿
                break;
            case 'freeze':
                $album = Album::where('state', 0); //顯示已經封存的文章
                break;
            default:
                $album = Album::where('state', 1)->orWhere('state', 2); //預設，全顯示
                break;
        }
        $albums = $album->orderBy('updated_at', 'desc')->paginate($page); //將找到的資料輸出成分頁
        return View('album.editList', compact('albums'));

    }

    //前台顯示相簿，可加上時間篩選
    public function showAlbumList(Request $request, $year = null, $month = null)
    {
        if ($request->has('limit')) {
            $page = $request->limit;
        } else {
            $page = 8;
        }
        if (is_null($year) && is_null($month)) {
            $albums = Album::Where('state', 1)->orderBy('created_at', 'desc')->paginate($page);
        } else if (is_null($month)) {
            $albums = Album::Where('created_at', 'like', $year . '-%')->Where('state', 1)->orderBy('created_at', 'desc')->paginate($page);
        } else {
            if (strlen($month) == 1) {
                $month = '0' . $month;
            }
            $albums = Album::Where('created_at', 'like', $year . '-' . $month . '-%')->Where('state', 1)->orderBy('created_at', 'desc')->paginate($page);
        }
        // if (count($albums) == 0) {
        //     return $this->makeJson(0, null, 'NO_ALBUMS');
        // } else {
        //     return $this->makeJson(1, $albums, null);
        // }
        return view('album.list', compact('albums', 'year', 'month'));
    }

    //顯示指定年月的所有相簿
    public function showAlbums($year, $month)
    {
        $albums = Album::Where('created_at', 'like', $year . '-' . $month . '-%')->get();
        if (count($albums) > 0) {
            return $this->makeJson(1, $albums, null);
        } else {
            return $this->makeJson(0, null, 'NO_ALBUMS');
        }
    }

    //顯示指定的單一相簿的編輯模式頁面
    public function showPhotosForEdit(Album $album)
    {
        $id = $album->id;
        $photos = Photo::Where('albumId', $id)->Where('state', 1)->orWhere('state', 2)->paginate(12);
        return View('album.photoEditList', compact('album', 'photos'));
    }

    //顯示指定的單一相簿，公開模式
    public function showPhotos(Album $album)
    {
        $id = $album->id;
        $photos = Photo::Where('albumId', $id)->Where('state', 1)->paginate(12);
        return View('album.show', compact('album', 'photos'));
    }

    // 回傳指定相片，通用
    public function getPhoto(Photo $photo)
    {
        if (is_null($photo)) {
            return $this->makeJson(0, null, 'KNOWN_PHOTO');
        } else {
            return $this->makeJson(1, $photo, null);
        }
    }

    //建立相簿側邊欄位用
    public function getList()
    {
        $albums = array(); //儲存最後送出去的資料的陣列
        $year_a = array(); //暫存每年各個月份資料的陣列
        $y = ''; //作為判斷依據的年份
        $m = ''; //作為判斷依據的月份
        $count = 0; //計次用變數
        $temp = Album::Select('created_at')->Where('state', 1)->orderBy('created_at', 'asc')->get(); //搜尋所有啟用中的相簿資料
        $first = $temp[0]['created_at']; //將第一筆資料的建立時間取出
        $y = substr($first, 0, 4); //取出年份作為依據
        $m = substr($first, 5, 2); //取出月份作為依據
        $startCount = true;
        foreach ($temp as $value) {
            if (!$startCount) {
                $count = 1;
                $startCount = true;
            }
            $newY = substr($value['created_at'], 0, 4); //取出目前資料的年份
            $newM = substr($value['created_at'], 5, 2); //取出目前資料的月份
            if ($newY == $y && $newM == $m) { //當年月均相同，即為同一月份中的相簿
                $count++; //當月相簿數量+1
            } else if ($newY == $y && $newM != $m) { //年份相同、月份不同，即同年中的另一個月份的相簿
                $m = str_replace('0', '', $m);
                $year_a[$m] = $count; //將上個月份的統計，以上個月的數字加入暫存陣列中
                $m = $newM; //將依據的月份更新為新的月份
                $startCount = false;
                // $count = 1; //計次歸零，並加上目前這一筆
            } else if ($newY != $y) { //年份不同，開始新的年份
                $m = str_replace('0', '', $m);
                $year_a[$m] = $count; //將上個月份的統計，以上個月的數字加入暫存陣列中
                $albums[$y] = $year_a; //將暫存陣列以年份的數字加入最終陣列
                $year_a = array(); //將暫存陣列歸零
                $y = $newY; //將依據的年份更新
                $m = $newM; //將依據的月份更新
                $startCount = false;
                // $count = 1; //計次歸零，並加上目前這一筆
            }
        }
        if ($startCount) {
            $m = str_replace('0', '', $m);
            $year_a[$m] = $count;
            $albums[$y] = $year_a;
        }
        return $albums; //回傳最終陣列
    }

    public function UploadImg(Request $request)
    {
        $id = $request->id; //取出目前正在上傳照片的相簿ID
        $pics = $request->allFiles(); //將所有傳送過來的檔案取出
        $links = array(); //建立最終要送出去的空陣列
        $errorCount = 0; //處理失敗的計次
        $count = 0; //輔助迴圈運行次數的數字
        if (count($pics) > 0) { //確認是否有檔案傳送過來
            foreach ($pics as $pic) {
                $type = $pic->extension(); //確認目前檔案的附檔名
                $name = str_replace('.tmp', '', $pic->getFilename()) . '.' . $type; //將暫存檔案的名子改為正式的名子
                $path = 'public/album/' . $id; //用目前的相簿ID作為檔案的儲存路徑
                switch ($type) { //判斷目前檔案的類型，確保檔案為相片檔或影片檔
                    case 'jpg':
                    case 'jpeg':
                    case 'png':
                    case 'gif':
                    case 'bmp':
                        $type = 1;
                        break;
                    case 'mp4':
                    case 'wmv':
                    case 'mpg':
                        $type = 2;
                        break;
                    default:
                        return $this->makeJson(0, ['target' => $name, 'type' => $type], 'NOT_SUPPORT_TYPE'); //當檔案非相片或影片時，回傳錯誤訊息
                }
                $result = Storage::putFileAs($path, $pic, $name); //將暫存檔保存至本地空間
                if (!$result) { //失敗時記錄錯誤次數，且不寫入資料表中
                    $errorCount++;
                } else {
                    $links[$count] = '/storage/album/' . $id . '/' . $name; //將顯示用的路徑存入最終陣列中
                    $result = Photo::create(['albumId' => $id, 'url' => $path . '/' . $name, 'type' => $type]); //將相簿ID、實際路徑、檔案類型寫入資料表中
                    if (!$result) { //寫入資料表失敗時回傳錯誤訊息
                        $errorCount++;
                    } else {
                        $count++;
                    }
                }
            }
            return $this->makeJson(1, $links, $errorCount); //全數處理完畢後，將最終陣列連同錯誤次數回傳至前台
        } else {
            return $this->makeJson(0, null, 'NO_FILE_UPLOAD');
        }
    }

    public function changePhoto(Photo $photo, Request $request)
    {
        try {
            $albumId = $photo->albumId; //取出目前正在上傳照片的相簿ID
            if ($request->hasFile('pic')) { //確認是否有檔案傳送過來
                $pic = $request->file('pic');
                $type = $pic->extension(); //確認目前檔案的附檔名
                $name = str_replace('.tmp', '', $pic->getFilename()) . '.' . $type; //將暫存檔案的名子改為正式的名子
                $path = 'public/album/' . $albumId; //用目前的相簿ID作為檔案的儲存路徑
                switch ($type) { //判斷目前檔案的類型，確保檔案為相片檔或影片檔
                    case 'jpg':
                    case 'jpeg':
                    case 'png':
                    case 'gif':
                    case 'bmp':
                        $type = 1;
                        break;
                    case 'mp4':
                    case 'wmv':
                    case 'mpg':
                        $type = 2;
                        break;
                    default:
                        return $this->makeJson(0, ['target' => $name, 'type' => $type], 'NOT_SUPPORT_TYPE'); //當檔案非相片或影片時，回傳錯誤訊息
                }
                $result = Storage::putFileAs($path, $pic, $name); //將暫存檔保存至本地空間
                if (!$result) { //失敗時記錄錯誤次數，且不寫入資料表中
                    return $this->makeJson(0, $result, 'SAVE_FILE_ERROR');
                }
                $result = $photo->update(['url' => $path . '/' . $name, 'type' => $type]); //將相簿ID、實際路徑、檔案類型寫入資料表中
                if (!$result) { //寫入資料表失敗時回傳錯誤訊息
                    return $this->makeJson(0, $result, 'INSERT_ERROR');
                }
                $link = '/storage/album/' . $albumId . '/' . $name; //紀錄顯示用的路徑

                return $this->makeJson(1, $link, null); //全數處理完畢後，將檔案位址回傳至前台
            } else {
                return $this->makeJson(0, null, 'NO_FILE_UPLOAD');
            }
        } catch (\Exception $th) {
            return $this->makeJson(0, $th, null);
        }
    }

    public function editImgInfo(Photo $photo, Request $request)
    {
        $params = $request->only('title', 'content');
        $result = $photo->update($params);
        if ($result) {
            return $this->makeJson(1, $photo, null);
        } else {
            return $this->makeJson(0, $result, 'UPDATE_ERROR');
        }
    }

    public function freeze(Album $album)
    {
        $result = $album->update(['state' => 0]);
        if ($result) {
            return $this->makeJson(1, null, null);
        } else {
            return $this->makeJson(0, $result, 'DELETE_ALBUM_ERROR');
        }
    }

    public function freezePhotos(Request $request)
    {
        try {
            $data = $request->data;
            $successCount = 0;
            $errorCount = 0;
            $errorMessage = array();
            if (gettype($data) == 'array') {
                foreach ($data as $photo) {
                    $result = Photo::Where('id', $photo)->update(['state' => 0]);
                    if ($result) {
                        $successCount++;
                    } else {
                        $errorCount++;
                        array_push($errorMessage, $result);
                    }
                }
            } else {
                $result = Photo::Where('id', $data)->update(['state' => 0]);
                if ($result) {
                    $successCount++;
                } else {
                    $errorCount++;
                    array_push($errorMessage, $result);
                }
            }

            return $this->makeJson(1, ['success' => $successCount, 'error' => $errorCount], $errorMessage);
        } catch (\Throwable $th) {
            return $this->makeJson(0, $th, null);
        }
    }
}