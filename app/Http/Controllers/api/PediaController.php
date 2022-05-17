<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PediaCategory;
use App\Models\PediaContent;
use App\Models\PediaGallery;
use App\Models\PediaItem;
use App\Models\PediaRemark;
use App\Models\PediaTag;
use App\Models\PediaTagType;
use App\Models\Tag_for_pedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PediaController extends Controller
{
    private function makeJson($state, $data = null, $msg = null)
    {
        return response()->json(['state' => $state, 'data' => $data, 'msg' => $msg])->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }

    //將暫存圖片移動至正式存放位置
    private function MovePic($id, $data, $target = null, $info = null, $type = null)
    {
        $user = Auth::id(); //取得目前使用者的ID，用以取得暫存檔所存放的位置
        $frontTempPath = 'storage/pedia/temp/' . $user; //前台用的暫存檔顯示位置
        $tempPath = 'public/pedia/temp/' . $user; //本地硬碟的暫存檔位置
        $newPath = 'public/pedia/' . $id; //正式的存放位置
        $newImage = str_replace($frontTempPath, $newPath, $data); //將前台顯示的位置路徑更改為正式的路徑
        $oldImage = str_replace($frontTempPath, $tempPath, $data); //將前台顯示的位置路徑更改為本地硬碟的暫存路徑
        $result = Storage::move($oldImage, $newImage); //將暫存檔案搬運至正式位置
        if ($result) {
            switch ($target) {
                case 'index':
                    $result = PediaItem::Where('id', $id)->get()->update(['image' => $newImage]); //當搬運的圖片為項目形象圖時，更新資料表中的資料
                    break;
                case 'gallery':
                    $params = $info; //取出從前台獲得的畫廊圖片的資訊
                    $params['itemId'] = $id; //設定畫廊所屬的項目ID
                    $params['url'] = $newImage; //路徑為實際存放位置
                    $params['type'] = $type; //獲得從前台取得的檔案類型
                    $result = PediaGallery::create($params); //寫進資料表中
                    if (is_null($result)) {
                        $result = false;
                    }
                    break;
            }
        }
        return $result;
    }

    //呼叫百科項目建立頁面
    public function callItemEditor($id = null)
    {
        $categories = PediaCategory::select('id', 'name')->where('state', 1)->get();
        $tags = PediaTag::select('id', 'name')->where('state', 1)->get();
        if (!is_null($id)) {
            $item = PediaItem::Where('id', $id)->first();
            return view('pedia.itemEditor', compact('categories', 'tags', 'item'));
        } else {
            return view('pedia.itemEditor', compact('categories', 'tags'));
        }

    }

    public function callContentEditor($fatherId, PediaContent $id = null, Request $request)
    {
        if (is_null($id)) {
            $sort = $request->sort;
            return view('pedia.contentEditor', compact('fatherId', 'sort'));
        } else {
            $sort = $id->sort;
            return view('pedia.contentEditor', compact('fatherId', 'id', 'sort'));
        }
    }

    public function callGalleryEditor($fatherId)
    {
        $galleries = PediaGallery::Where('itemId', $fatherId)->OrderBy('sort', 'asc')->get();
        return view('pedia.galleryEditor', compact('fatherId', 'galleries'));
    }

    public function callTagEditor(PediaTag $tag = null)
    {
        $types = PediaTagType::Where('state', 1)->get();
        if (is_null($tag)) {
            return view('pedia.tagEditor', compact('types'));
        } else {
            return view('pedia.tagEditor', compact('tag', 'types'));
        }
    }

    public function callTypeEditor(PediaTagType $type = null)
    {
        if (is_null($type)) {
            return view('pedia.typeEditor');
        } else {
            return view('pedia.typeEditor', compact('type'));
        }
    }

    public function preview($id)
    {
        $item = PediaItem::Where('id', $id)->Where('state', 1)->first();
        $types = PediaTagType::Where('state', 1)->get();
        $contents = $item->getContents();
        if ($contents->count() > 0) {
            $contents = $contents->Where('state', 1)->get();
        }

        // $contents = PediaContent::Where('itemId', $id)->Where('state', 1)->get();
        // $galleries = $item->getGalleries();
        $galleries = PediaGallery::Where('itemId', $id)->orderBy('sort', 'asc')->get();
        return view('pedia.preview', compact('item', 'contents', 'galleries', 'types'));
    }

    public function categoryList()
    {
        $cates = PediaCategory::Where('state', 1)->paginate(15);
        return view('pedia.categoryList', compact('cates'));
    }

    public function tagList()
    {
        $tags = PediaTag::Where('state', 1)->paginate(15);
        return view('pedia.tagList', compact('tags'));
    }

    public function typeList()
    {
        $types = PediaTagType::Where('state', 1)->paginate(15);
        return view('pedia.typeList', compact('types'));
    }

    //建立分類、標籤項目
    public function attrCreate(Request $request, $target)
    {
        $result = false;
        $params['name'] = $request->name;
        switch ($target) {
            case 'category':
                $result = PediaCategory::create($params);
                break;
            case 'tag':
                $params['typeId'] = $request->typeId;
                $result = PediaTag::create($params);
                break;
            case 'type':
                $result = PediaTagType::create($params);
                break;
            default:
                return $this->makeJson(0, null, 'UNKNOWN_CREATE_TYPE');
                break;
        }
        if (!is_null($result)) {
            return $this->makeJson(1, null, null);
        } else {
            return $this->makeJson(0, $result, 'CREATE_ERROR');
        }

    }

    //更新分類、標籤、百科項目
    public function attrUpdate($target, $id, Request $request)
    {
        $result = false;
        $params['name'] = $request->name;
        switch ($target) {
            case 'category':
                $result = PediaCategory::Where('id', $id)->first()->update($params);
                break;
            case 'tag':
                $result = PediaTag::Where('id', $id)->first()->update($params);
                break;
            case 'type':
                $result = PediaTag::Where('id', $id)->first()->update($params);
                break;
            default:
                return $this->makeJson(0, null, 'UNKNOWN_CREATE_TYPE');
                break;
        }
        if ($result) {
            return $this->makeJson(1, null, null);
        } else {
            return $this->makeJson(0, $result, 'UPDATE_ERROR');
        }

    }

    //凍結分類、標籤、百科項目
    public function Freeze($target, Request $request)
    {
        $params['state'] = 0; //將狀態設定為凍結
        $errorCount = 0; //錯誤計次
        $errorMessage = array(); //錯誤訊息陣列
        $data = $request->data; //取出要刪除的ID陣列
        foreach ($data as $id) {
            switch ($target) { //根據傳入的對象選擇更新對象
                case 'category':
                    $result = PediaCategory::Where('id', $id)->first()->update($params);
                    break;
                case 'tag':
                    $result = PediaTag::Where('id', $id)->first()->update($params);
                    break;
                case 'item':
                    $result = PediaItem::Where('id', $id)->first()->update($params);
                    break;
                default:
                    return $this->makeJson(0, null, 'UNKNOWN_FREEZE_TYPE');
                    break;
            }
            if (!$result) { //錯誤統計
                $errorMessage[$errorCount] = $result;
                $errorCount++;
            }
        }
        if ($errorCount == 0) {
            return $this->makeJson(1, null, null);
        } else {
            return $this->makeJson(0, $errorMessage, 'DELETE_ERROR:' . $errorCount);
        }

    }

    //徹底刪除分類、標籤
    public function attrDestroy($target, Request $request)
    {
        $errorCount = 0; //錯誤計次
        $errorMessage = array(); //錯誤訊息陣列
        $data = $request->data; //取出要刪除的ID陣列
        foreach ($data as $id) {
            switch ($target) {
                case 'category':
                    $result = PediaCategory::Where('id', $id)->get()->delete();
                    break;
                case 'tag':
                    $result = PediaTag::Where('id', $id)->get()->delete();
                    break;
                case 'item':
                    $result = $this->ItemDestroy($id); //用專屬函式將與項目關聯的內容全數刪除，失敗會回傳對應的錯誤訊息
                default:
                    return $this->makeJson(0, null, 'UNKNOWN_DESTROY_TYPE');
                    break;
            }
            if (!$result) { //錯誤統計
                $errorMessage[$errorCount] = $result;
                $errorCount++;
            }
        }
        if ($errorCount == 0) {
            return $this->makeJson(1, null, null);
        } else {
            return $this->makeJson(0, $errorMessage, 'DELETE_ERROR:' . $errorCount);
        }
    }

    private function ItemDestroy($id)
    {
        $errorMessage = '';
        $result = PediaItem::Where('id', $id)->get()->delete();
        if (!$result) {
            $errorMessage += $result;
        }

        $result = PediaContent::Where('itemId', $id)->get()->delete();
        if (!$result) {
            $errorMessage += $result;
        }

        $result = Tag_for_pedia::Where('itemId', $id)->get()->delete();
        if (!$result) {
            $errorMessage += $result;
        }

        $result = PediaGallery::Where('itemId', $id)->get()->delete();
        if (!$result) {
            $errorMessage += $result;
        }

        if ($errorMessage == '') {
            return true;
        } else {
            return $errorMessage;
        }

    }

    public function itemCreate(PediaItem $item = null, Request $request)
    {
        try {
            $params = $request->only('name', 'category');
            $update = false;
            // if (is_null($oldId)) {
            //     $params['version'] = 0;
            // } else {
            //     $oldItem = PediaItem::Select('version', 'fatherId')->Where('id', $oldId)->get()->first();
            //     $params['version'] = intval($oldItem->version) + 1;
            //     $fatherId = $oldItem->fatherId;
            //     if (is_null($fatherId)) {
            //         $params['fatherId'] = $oldId;
            //     } else {
            //         $params['fatherId'] = $fatherId;
            //     }
            // }
            if (is_null($item)) {
                $result = PediaItem::create($params);
                if ($result->id == '') {
                    return $this->makeJson(0, $result, 'CREATE_ERROR');
                }
                $item = $result;
            } else {
                $result = $item->update($params);
                if (!$result) {
                    return $this->makeJson(0, $result, 'UPDATE_ERROR');
                }
                $update = true;
            }
            $id = $item->id;
            //建立&儲存形象圖片
            $oldImage = $request->oldImage;
            if ($request->has('image')) {
                $image = $request->image;
                $imageName = str_replace('tmp', $image->extension(), $image->getFilename());
                $patch = '/public/pedia/' . $id;

                $result = Storage::putFileAs($patch, $image, $imageName);
                if (!$result) {
                    return $this->makeJson(0, $result, 'INDEX_SAVE_ERROR');
                }
                $result = $item->update(['image' => $patch . '/' . $imageName]);
                if (!$result) {
                    return $this->makeJson(0, $result, 'INDEX_UPDATE_ERROR');
                }
            } else { //沒有檔案的狀況
                if (is_null($oldImage) || $oldImage == '') {
                    //不存在就圖片資料＝>新建立的未附圖項目或刪掉圖片的舊項目
                    $image = '/public/pedia/noImage.png';
                } else {
                    //圖片保持為舊圖片
                    $image = str_replace('storage', 'public', $oldImage);
                }
                $result = $item->update(['image' => $image]);
                if (!$result) {
                    return $this->makeJson(0, $result, 'DEFAULT_INDEX_INSERT_ERROR');
                }
            }
            if ($update) { //有舊資料
                foreach (json_decode($request->deleteList) as $tag) {
                    $result = Tag_for_pedia::Where('itemId', $id)->Where('tagId', $tag)->first()->delete();
                    if (!$result) {
                        return $this->makeJson(0, $result, 'OLD_TAGS_DELETE_ERROR');
                    }
                }
            }
            //建立項目所屬標籤
            $tags = json_decode($request->tags);
            foreach ($tags as $tag) {
                if (count(Tag_for_pedia::Where('itemId', $id)->Where('tagId', $tag)->get()) < 1) {
                    $result = Tag_for_pedia::create(['itemId' => $id, 'tagId' => $tag, 'state' => 1]);
                    if (!$result) {
                        return $this->makeJson(0, $result, 'PEDIA_ITEM_CREATE_ERROR');
                    }
                }
            }

            return $this->makeJson(1, $item, 'PEDIA_ITEM_CREATE_SUCCESS');
        } catch (\Exception $e) {
            return $this->makeJson(0, ['msg' => $e->getMessage(), 'line' => $e->getLine()], 'CODE_ERROR');
        }
    }

    public function contentCreate(PediaContent $id, Request $request)
    {
        // return $this->makeJson(0, $request->deleteImage, null);
        try {
            if ($request->title == '') {
                return $this->makeJson(0, null, 'NO_TITLE');
            }

            if ($request->content == '') {
                return $this->makeJson(0, null, 'NO_CONTENT');
            }
            $params = $request->only('itemId', 'title', 'content', 'sort');
            $params['state'] = 1;
            if (!is_null($request->remarks)) {
                $re = explode(',', $request->remarks);
                $remarks = base64_encode(serialize($re));
                $params['remark'] = $remarks;
            }
            if (is_null($id)) {
                $result = PediaContent::create($params);
                if ($result->id == '') {
                    return $this->makeJson(0, $result, 'CREATE_ERROR');
                }
                $temp = $result;
                $id = $result->itemId;
            } else {
                $result = $id->update($params);
                if (!$result) {
                    return $this->makeJson(0, $result, 'UPDATE_ERROR');
                }
                $temp = $id;
                $id = $id->id;
            }
            if (is_null($id)) {
                $gallery = array();
            } else {
                $gallery = $temp->getGalleries();
            }
            $savePath = '/public/pedia/' . $id;
            $path = '/storage/pedia/' . $id;
            if (count($request->allFiles()) > 0) {
                $galleries = $request->allFiles();
                foreach ($galleries as $key => $g) {
                    $fileName = str_replace('tmp', $g->extension(), $g->getFilename());
                    Storage::putFileAs($savePath, $g, $fileName);
                    $gallery[$key] = ([$path . '/' . $fileName, $request[$key . 'c']]);
                }
            }
            $deleteImage = $request->deleteImage;
            if (!is_null($deleteImage)) {
                $deleteImage = explode(',', $deleteImage);
                foreach ($deleteImage as $d) {
                    unset($gallery[$d]);
                }
            }
            $gallery = base64_encode(serialize($gallery));
            $result = $temp->update(['gallery' => $gallery]);
            if (!$result) {
                return $this->makeJson(0, $result, 'GALLERY_UPDATE_ERROR');
            }

            return $this->makeJson(1, null, null);

        } catch (\Exception $th) {
            return $this->makeJson(0, $th->getMessage(), null);
        }

    }

    public function galleryCreate(Request $request)
    {
        try {
            $fatherId = $request->fatherId;
            $gTemp = explode(',', $request->galleries);
            $galleries = array();
            $temp = array();
            $count = 0;
            foreach ($gTemp as $t) {
                array_push($temp, $t);
                $count++;
                if ($count == 2) {
                    array_push($galleries, $temp);
                    $count = 0;
                    $temp = array();
                }
            }
            $sort = PediaGallery::Where('itemId', $fatherId)->OrderBy('sort', 'desc')->get()->first();
            if (is_null($sort)) {
                $sort = 1;
            } else {
                $sort = (int) ($sort->sort) + 1;
            }
            // return $this->makeJson(0, $sort, null);
            $savePath = '/public/pedia/' . $fatherId;
            $path = '/storage/pedia/' . $fatherId;
            foreach ($galleries as $g) {
                $file = $g[0];
                $caption = $g[1];
                if ($request->hasFile($file)) {
                    $file = $request->file($file);
                    $filename = str_replace('tmp', $file->extension(), $file->getFilename());
                    $result = Storage::putFileAs($savePath, $file, $filename);
                    if (!$result) {
                        return $this->makeJson(0, $result, 'SAVE_FILE_ERROR');
                    }
                    $file = $path . '/' . $filename;
                }
                $result = PediaGallery::create(['itemId' => $fatherId, 'url' => $file, 'caption' => $caption, 'sort' => $sort, 'type' => 1]);
                $sort++;
            }
            return $this->makeJson(1, null, null);
        } catch (\Exception $th) {
            return $this->makeJson(0, $th->getMessage(), 'CODE_ERROR');
        }
    }

    public function galleryDelete(PediaGallery $id)
    {
        try {
            $path = $id->url;
            $result = $id->delete();
            if (!$result) {
                return $this->makeJson(0, $result, 'TABLE_DELETE_ERROR');
            }
            $result = Storage::delete($path);
            if (!$result) {
                return $this->makeJson(0, $result, 'FILE_DELETE_ERROR');
            }
            return $this->makeJson(1, null, null);
        } catch (\Exception $th) {
            return $this->makeJson(0, $th->getMessage(), 'CODE_ERROR');
        }
    }

    public function itemCreate_Older(Request $request)
    {
        $params = $request->only(['name', 'category']); //只取出項目的名稱與分類
        if (!is_null($request->fatherId)) { //當傳送過來的資料有fatherId時，表示為項目資料更新
            $params['fatherId'] = $request->fatherId; //設定fatherId
            $params['version'] = $request->version++; //將目前的版本數加1
        }
        $result = PediaItem::create($params); //將基本資料寫進資料表
        if (!is_null($result)) {
            $id = $result->id; //成功寫入後，取得該項目的ID
            $result = $this->MovePic($id, $request->image, 'index'); //將項目形象圖移至正式存放位置
            if ($result) {
                $errorCount = 0; //用來計算錯誤次數的變數
                $errorMessage = array(); //存放錯誤訊息的陣列
                $tags = $request->tags; //取出該項目所屬的標籤陣列
                foreach ($tags as $tag) {
                    $result = Tag_for_pedia::create(['itemId' => $id, 'tagId' => $tag]); //將標籤資料依序寫進資料表中
                    if (!$result) { //若資料寫入出錯，將錯誤資訊記錄下來，並增加錯誤計次
                        $errorCount++;
                        array_push($errorMessage, $result);
                    }
                }
                if ($errorCount == 0) { //若錯誤次數為0，則繼續執行後續處理
                    $titles = $request->titles; //取出各小項目的陣列
                    $contents = $request->contents; //取出各小項目的內容陣列
                    for ($i = 0; $i < count($titles); $i++) {
                        $result = PediaContent::create(['itemId' => $id, 'title' => $titles[$i], 'content' => $contents[$i], 'sort' => $i]); //依序取出小項目的標題與內容，順序用陣列內的順序
                        if (!$result) { //錯誤統計
                            $errorCount++;
                            array_push($errorMessage, $result);
                        }
                    }
                    if ($errorCount == 0) { //無錯誤時
                        if ($request->has('pics')) { //確認前台是否有傳送文章的圖片
                            $pics = $request->pics; //將項目內的有插入的文章取出
                            foreach ($pics as $pic) {
                                $result = $this->MovePic($id, $pic); //將暫存圖片移動至實際存放位子
                                if (!$result) { //錯誤統計
                                    $errorCount++;
                                    array_push($errorMessage, $result);
                                }
                            }}
                        if ($errorCount == 0) {
                            $galleries = $request->galleries; //取出畫廊圖片陣列，陣列內容須為「圖片位址」+「媒體類型」
                            $infoList = $request->infoList; //取出畫廊圖片資訊陣列，陣列內容須為「標題」+「內文」
                            for ($i = 0; $i < count($galleries); $i++) {
                                $result = $this->MovePic($id, $galleries[$i]['url'], 'gallery', $infoList[$i], $galleries[$i]['type']); //移動暫存圖片
                                if (!$result) { //錯誤統計
                                    $errorCount++;
                                    array_push($errorMessage, $result);
                                }
                                if ($errorCount == 0) { //0錯誤時
                                    $remarks = $request->remarks; //取出註解二維陣列
                                    foreach ($remarks as $remark) { //陣列內容須為註解對象標號、註解內文、註解是否擁有連結(預設為0，選填)、連結(選填)
                                        $remark['itemId'] = $id; //加上項目ID
                                        $result = PediaRemark::create($remark); //寫入資料表
                                        if (is_null($result)) { //錯誤統計
                                            $errorCount++;
                                            array_push($errorMessage, $result);
                                        }
                                        if ($errorCount == 0) { //無錯誤時
                                            return $this->makeJson(1, ['id' => $id], null); //回傳項目ID
                                        } else {
                                            return $this->makeJson(0, $errorMessage, 'REMARK_INSERT_ERROR:' . $errorCount); //回傳錯誤資訊及錯誤計次
                                        }
                                    }
                                } else {
                                    return $this->makeJson(0, $errorMessage, 'GALLERY_SAVE_ERROR:' . $errorCount); //回傳錯誤資訊及錯誤計次

                                }
                            }
                        } else {
                            return $this->makeJson(0, $errorMessage, 'CONTENT_PIC_SAVE_ERROR:' . $errorCount); //回傳錯誤資訊及錯誤計次
                        }
                    } else {
                        return $this->makeJson(0, $errorMessage, 'CONTENT_CREATE_ERROR:' . $errorCount); //回傳錯誤資訊及錯誤計次

                    }
                } else {
                    return $this->makeJson(0, $errorMessage, 'TAG_INSERT_ERROR:' .
                        $errorCount); //回傳錯誤資訊及錯誤計次
                }
            } else {
                return $this->makeJson(0, $result, 'IMAGE_SAVE_ERROR'); //回傳錯誤資訊及錯誤計次
            }
        } else {
            return $this->makeJson(0, $result, 'ITEM_CREATE_ERROR'); //回傳錯誤資訊及錯誤計次
        }
    }

    //顯示項目資訊，依據需求顯示一般頁面或編輯頁面
    public function itemShow($id, $action = null)
    {
        $item = PediaItem::Where('id', $id)->Where('state', 1)->get();
        if (count($item) == 0) {
            return 'PEDIA IS DELETE'; //當沒有結果時
        }
        $item = $item->first();
        $categories = PediaCategory::select('id', 'name')->where('state', 1)->get();
        $tags = PediaTag::select('id', 'name')->where('state', 1)->get();
        $tags_for_item = Tag_for_pedia::Join('pedia_tags', 'tag_for_pedias.tagId', '=', 'pedia_tags.id')->Where('itemId', $id)->get(); //尋找項目所屬的標籤
        $content = PediaContent::Where('itemId', $id)->get(); //尋找項目所屬的內容
        $galleries = PediaGallery::Where('itemId', $id)->get(); //尋找項目所屬的畫廊圖片
        $remarks = PediaRemark::Where('itemId', $id)->get(); //尋找項目所屬的註解
        if ($action == 'edit') { //有傳入編輯指令時，回傳編輯畫面
            return view('pedia.itemEditor', compact('item', 'categories', 'tags', 'tags_for_item', 'content', 'galleries', 'remarks'));
        } else {
            return view('pedia.show', compact('item', 'categories', 'tags', 'tags_for_item', 'content', 'galleries', 'remarks'));
        }
    }

    public function itemListBackend()
    {
        $items = PediaItem::Where('state', 1)->orderBy('created_at', 'DESC')->get();
        return view('pedia.listBackend', compact('items'));
    }
}