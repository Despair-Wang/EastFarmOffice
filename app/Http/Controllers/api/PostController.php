<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\PostTag;
use App\Models\Tag_for_post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    private function makeJson($state, $data = null, $msg = null)
    {
        return response()->json(['state' => $state, 'data' => $data, 'msg' => $msg])->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }

    private function beArray($array, $colName)
    {
        $response = array();
        if (gettype($array) == 'object') {
            for ($i = 0; $i < count($array); $i++) {
                $response[$i] = $array[$i][$colName];
            };
        } else {
            $response[0] = gettype($array);
        }
        return $response;
    }

    public function callPostEditor()
    {
        $tempPath = 'public/temp/' . Auth::id(); //該使用者專用的暫存資料夾，避免與其他帳號的檔案衝突
        if (!Storage::exists($tempPath)) { //如果使用者的資料夾不存在時建立
            Storage::makeDirectory($tempPath);
        }
        $categories = PostCategory::Select('id', 'name')->where('state', '1')->get(); //尋找開放的分類
        $tags = PostTag::Select('id', 'name')->where('state', '1')->get(); //尋找開放的標籤
        return View('post.postEditor', compact('categories', 'tags'));
    }

    public function callPostUpdate(Post $post, $action)
    {
        $tempPath = 'public/temp/' . Auth::id(); //該使用者專用的暫存資料夾，避免與其他帳號的檔案衝突
        if (!Storage::exists($tempPath)) { //如果使用者的資料夾不存在時建立
            Storage::makeDirectory($tempPath);
        }
        $categories = PostCategory::Select('id', 'name')->where('state', 1)->get(); //尋找開放的分類
        $tags = PostTag::Select('id', 'name')->where('state', 1)->get(); //尋找開放的標籤
        $tagForPost = Tag_for_post::Select('post_tags.id', 'post_tags.name')->Where('postId', $post->id)->Join('post_tags', 'tag_for_posts.tagId', '=', 'post_tags.id')->get();
        return View('post.postEditor', compact('post', 'categories', 'tags', 'tagForPost', 'action'));
    }

    public function cateCreate(Request $request)
    {
        $params = $request->only('name', 'content'); //取出標籤名稱與說明內容
        $result = PostCategory::create($params); //建立資料
        if ($result) {
            return $this->makeJson(1, null, null);
        } else {
            return $this->makeJson(0, $result, 'CREATE_ERROR'); //
        }
    }

    public function tagCreate(Request $request)
    {
        $params = $request->only('name', 'content'); //取出分類名稱與說明內容
        $result = PostTag::create($params); //建立資料
        if ($result) {
            return $this->makeJson(1, null, null);
        } else {
            return $this->makeJson(0, $result, 'CREATE_ERROR'); //
        }
    }

    public function cateUpdate(PostCategory $id, Request $request)
    {
        $params = $request->only('name', 'content'); //取出分類名稱與說明內容
        $result = $id->update($params); //更新對象ID的資料
        if ($result) {
            return $this->makeJson(1, null, null);
        } else {
            return $this->makeJson(0, $result, 'UPDATE_ERROR'); //
        }
    }

    public function tagUpdate(PostTag $id, Request $request)
    {
        $params = $request->only('name', 'content'); //取出標籤名稱與說明內容
        $result = $id->update($params); //更新對象ID的資料
        if ($result) {
            return $this->makeJson(1, null, null);
        } else {
            return $this->makeJson(0, $result, 'UPDATE_ERROR'); //
        }
    }

    public function freezeCheck(Request $request, $target)
    {
        $data = $request->data;
        $result = array();
        $count = 0;
        $type = gettype($data);
        if ($type == 'array') {
            switch ($target) {
                case 'category':
                    foreach ($data as $id) {
                        $posts = Post::select('id', 'title')->where('category', $id)->where('state', 1)->get();
                        foreach ($posts as $post) {
                            array_push($result, ['id' => $post->id, 'title' => $post->title]);
                            $count++;
                        }
                    }
                    break;
                case 'tag':
                    foreach ($data as $id) {
                        $posts = Tag_for_post::where('tagId', $id)->where('state', 1)->get();
                        foreach ($posts as $post) {
                            $post = $post->getPost;
                            array_push($result, ['id' => $post->id, 'title' => $post->title]);
                            $count++;
                        }
                    }
                    break;
                default:
                    return $this->makeJson(0, null, 'UNKNOWN_TYPE');
                    break;
            }
        } else if ($type == 'string') {
            switch ($target) {
                case 'category':
                    $posts = Post::select('id', 'title')->where('category', $data)->where('state', 1)->get();
                    foreach ($posts as $post) {
                        array_push($result, ['id' => $post->id, 'title' => $post->title]);
                        $count++;
                    }
                    break;
                case 'tag':
                    $posts = Tag_for_post::where('tagId', $data)->where('state', 1)->get();
                    foreach ($posts as $post) {
                        $post = $post->getPost;
                        array_push($result, ['id' => $post->id, 'title' => $post->title]);
                        $count++;
                    }
                    break;
                default:
                    return $this->makeJson(0, $target, 'UNKNOWN_TYPE');
                    break;
            }
        } else {
            return $this->makeJson(0, null, 'KNOWN_DATA');
        }

        return $this->makeJson(1, ['count' => $count, 'result' => $result], null);

    }

    public function freeze(Request $request, $target)
    {
        try {
            $result = false; //預先建立儲存處理結果的變數，以免出現狀況外的資料
            $data = $request->data; //取出前台傳來的資訊
            $successCount = 0; //記錄執行成功的次數
            $falseCount = 0; //紀錄執行失敗的次數
            $type = gettype($data); //確認傳來的資料是字串還是陣列
            if ($type == 'array') { //資料為陣列，凍結負數對象
                foreach ($data as $value) {
                    switch ($target) {
                        case 'category':
                            $result = $this->cateFreezed($value); //使用分類的函式處理
                            break;
                        case 'tag':
                            $result = $this->TagFreezed($value); //使用標籤的函式處理
                            break;
                        case 'post':
                            $result = $this->PostFreezed($value); //使用文章的函式處理
                            break;
                    }
                    if ($result) {
                        $successCount++; //成功時累加成功次數
                    } else {
                        $falseCount++; //錯誤時累加錯誤次數
                    }
                }
            } else if ($type == 'string') {
                switch ($target) {
                    case 'category':
                        $result = $this->cateFreezed($data); //使用分類的函式處理
                        break;
                    case 'tag':
                        $result = $this->TagFreezed($data); //使用標籤的函式處理
                        break;
                    case 'post':
                        $result = $this->PostFreezed($data); //使用文章的函式處理
                        break;
                }
                if ($result) {
                    $successCount++;
                } else {
                    $falseCount++;
                }
            }
            return $this->makeJson(1, ['success' => $successCount, 'false' => $falseCount], null); //回傳成功與失敗的次數給前台
        } catch (\Throwable $e) {
            return $this->makeJson(0, $e, 'DELETE_ERROR');
        }
    }

    public function cateFreezed($id)
    {
        return PostCategory::find($id)->update(['state' => 0]); //改變狀態為凍結
    }

    public function TagFreezed($id)
    {
        return PostTag::find($id)->update(['state' => 0]); //改變狀態為凍結
    }

    public function PostFreezed($id)
    {
        try {
            $result = Post::find($id)->update(['state' => 0]); //改變狀態為凍結
            if ($result) { //當文章狀態改變成功後，修改對應的標籤狀態
                $tags = $id->getTags; //透過Model關聯性來找出對應的標籤
                foreach ($tags as $tag) {
                    $result = $tag->update(['state' => 0]);
                    if (!$result) {
                        break;
                    }
                }
            }
            return $result;
        } catch (\Throwable $e) {
            return $e;
        }
    }

    public function postCreate(Request $request, $action = null)
    {
        $id = '';
        try {
            $params = $request->only('title', 'category'); //取出文章的標題、分類(要建立的文章是更新後的文章的情況)
            $params['content'] = '';
            if ($action == 'update') { //當指定動作為更新時
                $params['fatherId'] = $request->postId; //加入原始文章ID
                $params['version'] = $request->version++; //版本數增加1
            }
            $params['creator'] = Auth::id(); //將目前使用者的ID加入陣列準備寫入
            $tags = $request->tags; //將文章的標籤群取出存為變數
            $tempPath = 'public/temp/' . Auth::id(); //暫存圖片的本地所在資料夾
            $temp = Storage::allFiles($tempPath); //將暫存資料夾中的所有檔案取出
            $pics = $request->pics; //將前台傳來的，文章中的所有圖片陣列取出存為變數
            if ($action == 'rewrite') {
                $rewritePost = Post::Where('id', $request->postId)->get()->first();
                $result = $rewritePost->update($params);
            } else {
                $result = Post::create($params); //將資料寫入Post的資料表中
            }
            if (!is_null($result) || $result != false) { //當資料寫入成功時執行
                if ($action == 'rewrite') {
                    $id = $request->postId;
                    $newPost = $rewritePost;
                } else {
                    $id = $result->id; //獲得該文章的ID
                    $newPost = $result;
                }
                $path = 'public/post/' . $id; //設定該文章圖片的本地正式儲存位置
                foreach ($temp as $value) { //取出暫存資料夾中的所有圖片
                    $tempName = str_replace('public', '/storage', $value); //將暫存資料夾的檔案路徑修改成與前台相同的路徑格式，方便比對
                    if (isset($pics) && gettype($pics) == 'array') { //確認前台有圖片時才執行
                        foreach ($pics as $pic) { //將文章內有的圖檔路徑一一取出
                            if ($tempName == $pic) { //確定該暫存圖有放在文章內時
                                $result = Storage::move($value, str_replace($tempPath, $path, $value)); //移動檔案到正式儲存位置
                                break; //結束，後面不用跑了
                            }
                        }
                    }
                    if (!$result) { //若圖片移動失敗時報錯
                        $newPost->delete();
                        return $this->makeJson(0, $result, 'MOVE_FILE_ERROR');
                    }
                }
                // return $this->makeJson(0, ['ser' => '/storage/temp/'+Auth::id(), 'rep' => '/'+$path, 'contentType' => $request->content], 'CODE_CHECK');
                $content = str_replace('/storage/temp/' . Auth::id(), '/' . str_replace('public', 'storage', $path), $request->content);
                $newPost->update(['content' => $content]);
                if ($action == 'update') {
                    $old = Post::Where('id', $request->postId)->get()->first(); //從資料庫中找出修改前的舊文章
                    $result = $old->update(['state' => 0]); //修改文章狀態為凍結，不刪除資料
                    if (!$result) { //修改狀態失敗時報錯
                        $newPost->delete();
                        return $this->makeJson(0, $result, 'FREEZE_OLDER_ERROR');
                    }
                }
                // foreach ($tags as $tag) { //將附在文章上的標籤全數取出
                //     $result = Tag_for_post::create(['postId' => $id, 'tagId' => $tag]); //將文章與標籤關係寫進資料表中
                //     if (!$result) { //寫入標籤失敗時報錯
                //         $newPost->delete();
                //         return $this->makeJson(0, $result, 'TAG_PASTE_ERROR');
                //     }
                // }
                Cache::put(Auth::id(), $tags);
                if ($request->has('image') && !is_null($request->image) && $request->image != '/storage/post/default/default.jpg') {
                    $saveImage = str_replace('temp/' . Auth::id(), 'post/' . $id, $request->image);
                    $image = str_replace('storage', 'public', $request->image); //取出文章代表圖像
                    $result = Storage::move($image, str_replace($tempPath, $path, $image)); //移動檔案到正式儲存位置
                    if (!$result) {
                        return $this->makeJson(0, $result, 'IMAGE_SAVE_ERROR');
                    }
                    $result = Storage::delete(Storage::allFiles($tempPath)); //文章內所有的圖片儲存完畢後，資料夾內剩餘的檔案
                    if (!$result) { //刪除剩餘圖片失敗時報錯
                        $newPost->delete();
                        return $this->makeJson(0, $result, 'DELETE_TEMP_ERROR');
                    }
                    $result = $newPost->update(['image' => $saveImage]);
                    if (!$result) {
                        return $this->makeJson(0, $result, 'IMAGE_UPDATE_TO_TABLE_ERROR');
                    }
                }
                return $this->makeJson(1, ['id' => $id], 'POST_CREATE_SUCCESS'); //文章完整建立完成
            } else {
                return $this->makeJson(0, $result, 'POST_CREATE_ERROR'); //文章寫入資料庫失敗時報錯
            }
        } catch (\Throwable $ex) {
            // return $this->makeJson(0, ['Message' => $ex->getMessage(), 'Code' => $ex->getCode(), 'Line' => $ex->getLine()], $ex->getMessage()); //程式碼錯誤
            Post::where('id', $id)->get()->first()->delete();
            return $this->makeJson(0, ['Message' => $ex], 'ERROR'); //程式碼錯誤
        }

    }

    public function saveForNext(Post $post)
    {
        $tags = Cache::get(Auth::id());
        foreach ($tags as $tag) {
            $result = PostTag::create(['postId' => $post->id, 'tagId' => $tag]);
            if (!$result) {
                return $this->makeJson(0, $result, null);
            }
        }
        return $this->makeJson(1, ['id' => $post->id], null);

    }

    public function beComplete(Post $post)
    {
        try {
            $result = $post->update(['state' => 1]);
            if ($result) {
                $tags = Cache::get(Auth::id());
                foreach ($tags as $tag) {
                    $result = Tag_for_post::create(['postId' => $post->id, 'tagId' => $tag, 'state' => 1]);
                    if (!$result) {
                        return $this->makeJson(0, $result, null);
                    }
                }
                Cache::forget(Auth::id());
                Cache::forget(Auth::id() . 'tag');
                return $this->makeJson(1, ['id' => $post->id], null);
            } else {
                return $this->makeJson(0, $result, null);
            }
        } catch (\Throwable $th) {
            return $this->makeJson(0, $th, null);
        }
    }

    public function release(Post $post)
    {
        $result = $post->update(['state' => 1]);
        if (!$result) {
            return $this->makeJson(0, $result, null);
        }
        $id = $post->id;
        $result = Tag_for_post::Where('postId', $id)->update(['state' => 1]);
        if (!$result) {
            return $this->makeJson(0, $result, 'TAG_RELEASE_FAILED');
        }
        return $this->makeJson(1, ['id' => $post->id], null);

    }

    public function makePicTemp(Request $request)
    {
        try {
            $id = Auth::id();
            $tempPath = '/public/temp/' . $id; //建立圖片暫存資料夾，依使用者區分
            $data = $request->File('pic'); //獲得前台傳來的檔案，前台圖片統一name設為pic
            $name = str_replace('.tmp', '', $data->getFilename()) . '.' . $data->extension(); //取得圖片暫存檔的檔名，並將暫存的附檔名改為正確的附檔名
            Storage::putFileAs($tempPath, $data, $name); //將暫存檔轉存成正式的檔案
            $temp = array('errno' => 0, 'data' => array(array('url' => '/storage/temp/' . $id . '/' . $name, 'alt' => 'null', 'href' => 'null'))); //依照wangEditor要求的格式整理資料
            return json_encode($temp); //轉成JSON字串後回傳
        } catch (\Throwable $th) {
            $data = $th->getMessage(); //獲得錯誤的訊息
            $temp = array('errno' => 1, 'data' => array(array('url' => $data, 'alt' => 'null', 'href' => 'null'))); //errno = 1時為錯誤
            return json_encode($temp);
        }
    }

    public function deleteFrontTempPic(Request $request)
    {
        $image = $request->image;
        $result = Storage::delete(str_replace('storage', 'public', $image));
        if ($result) {
            return $this->makeJson(1, null, null);
        } else {
            return $this->makeJson(0, $result, 'TEMP_PIC_DELETE_ERROR');
        }
    }

    public function show($id, $action = null)
    {
        switch ($action) {
            case 'preview':
                $post = Post::Where('id', $id)->get()->first();
                break;
            case null:
                $post = Post::Where('id', $id)->Where('state', 1)->get()->first();
                break;
            default:
                return 'UNKNOWN COMMAND';
                break;
        }
        if (is_null($post)) {
            return 'UNKNOWN POST';
        };
        // $post = $post->first();
        $tags = Tag_for_post::Where('postId', $id)->Join('post_tags', 'tag_for_posts.tagId', '=', 'post_tags.id')->get();
        return view('post.show', compact('post', 'tags', 'action'));
    }

    public function preview($id)
    {
        $post = Post::Where('id', $id)->get()->first();

        if (is_null($post)) {
            return 'UNKNOWN POST';
        };
        $temp = Cache::get(Auth::id());
        $tags = array();
        foreach ($temp as $tag) {
            $result = PostTag::where('id', $tag)->get()->first();
            array_push($tags, $result);
        }
        Cache::put(Auth::id() . 'tag', $tags);
        return view('post.preview', compact('post', 'tags'));

    }

    public function getContent($id)
    {
        $post = Post::Select('content')->Where('id', $id)->get()->first();
        $tags = Tag_for_post::Select('tagId')->Where('postId', $id)->get();
        $tags = $this->beArray($tags, 'tagId');
        if (is_null($post)) {
            return $this->makeJson(0, null, 'UNKNOWN POST');
        } else {
            return $this->makeJson(1, ['content' => $post['content'], 'tags' => $tags], null);
        }
    }

    //編輯模式用的文章一覽
    public function postList(Request $request, $type = null)
    {
        if ($request->has('limit')) {
            $page = $request->limit; //接收前台傳來的顯示頁面數
        } else {
            $page = 10; //預設為顯示10筆文章,後台文章篇幅小，適合多顯示
        }

        switch ($type) { //依據前台要求更改顯示對象
            case 'done':
                $post = Post::where('state', 1); //顯示已完成的公開文章
                break;
            case 'undone':
                $post = Post::where('state', 2); //顯示未完成的草稿
                break;
            case 'freeze':
                $post = Post::where('state', 0); //顯示已經封存的文章
                break;
            default:
                $post = Post::where('state', 1)->orWhere('state', 2); //預設，全顯示
                break;
        }
        $posts = $post->orderBy('updated_at', 'desc')->paginate($page)->withPath($type); //將找到的資料輸出成分頁
        return View('post.editList', compact('posts'));
    }

    public function postCategoryList(Request $request)
    {
        if ($request->has('limit')) {
            $page = $request->limit; //接收前台傳來的顯示頁面數
        } else {
            $page = 20; //預設為顯示20筆標籤
        }
        switch ($request->type) { //依據前台要求更改顯示對象
            case 'done':
                $cate = PostCategory::where('state', 1); //顯示已完成的公開文章
                break;
            case 'undone':
                $cate = PostCategory::where('state', 2); //顯示未完成的草稿
                break;
            case 'freeze':
                $cate = PostCategory::where('state', 0); //顯示已經封存的文章
                break;
            default:
                $cate = PostCategory::where('state', 1)->orWhere('state', 2); //預設，全顯示
                break;
        }
        $cates = $cate->orderBy('updated_at', 'desc')->paginate($page); //將找到的資料輸出成分頁
        return View('post.categoryList', compact('cates'));
    }

    public function postTagList(Request $request)
    {
        if ($request->has('limit')) {
            $page = $request->limit; //接收前台傳來的顯示頁面數
        } else {
            $page = 20; //預設為顯示20筆標籤
        }
        switch ($request->type) { //依據前台要求更改顯示對象
            case 'done':
                $tag = PostTag::where('state', 1); //顯示已完成的公開文章
                break;
            case 'undone':
                $tag = PostTag::where('state', 2); //顯示未完成的草稿
                break;
            case 'freeze':
                $tag = PostTag::where('state', 0); //顯示已經封存的文章
                break;
            default:
                $tag = PostTag::where('state', 1)->orWhere('state', 2); //預設，全顯示
                break;
        }
        $tags = $tag->orderBy('updated_at', 'desc')->paginate($page); //將找到的資料輸出成分頁
        return View('post.tagList', compact('tags'));
    }

    //前台用文章一覽
    public function showPostList(Request $request)
    {
        if ($request->has('limit')) {
            $page = $request->limit; //接收前台傳來的顯示頁面數
        } else {
            $page = 6; //預設為顯示6筆文章
        }
        $posts = Post::where('state', 1)->orderBy('updated_at', 'desc')->paginate($page);
        return View('post.list', compact('posts'));
    }

    public function showByCategory($id, Request $request)
    {
        $cate = PostCategory::where('id', $id)->where('state', 1)->get();
        if (count($cate) < 1) {
            return View('post.unfound');
        }
        $cate = $cate->first();
        if ($request->has('limit')) {
            $page = $request->limit; //接收前台傳來的顯示頁面數
        } else {
            $page = 6; //預設為顯示6筆文章
        }

        $posts = Post::where('category', $id)->where('state', 1);
        $count = count($posts->get());
        $posts = $posts->orderBy('updated_at', 'desc')->paginate($page);
        return View('post.list', compact('posts', 'cate', 'count'));
    }

    public function showByTag($id, Request $request)
    {
        $targetTag = PostTag::where('id', $id)->where('state', 1)->get();
        if (count($targetTag) < 1) {
            return View('post.unfound');
        }
        $targetTag = $targetTag->first();
        if ($request->has('limit')) {
            $page = $request->limit; //接收前台傳來的顯示頁面數
        } else {
            $page = 6; //預設為顯示6筆文章
        }
        $posts = Tag_for_post::where('tagId', $id)->where('state', 1);
        // $count = count($posts->get());
        $count = $posts->count();
        $posts = $posts->orderBy('updated_at', 'desc')->paginate($page);
        return View('post.list', compact('posts', 'targetTag', 'count'));
    }
}