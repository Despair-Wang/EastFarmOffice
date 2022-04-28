<?php

use App\Http\Controllers\Api\AlbumController;
use App\Http\Controllers\Api\CustomUserController;
use App\Http\Controllers\Api\GoodController;
use App\Http\Controllers\Api\PediaController;
use App\Http\Controllers\Api\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('good')->group(function () {
        Route::post('/addCart', [GoodController::class, 'addCart']);
        Route::post('/cartChange', [GoodController::class, 'cartChange']);
        Route::post('/order', [GoodController::class, 'orderCreate']);
    });
    Route::post('/order/report', [GoodController::class, 'reportPaid']);
    Route::post('/changePassword', [CustomUserController::class, 'changePassword']);
    Route::post('/changeInfo', [CustomUserController::class, 'changeInfo']);
    Route::get('/getUserInfo', [GoodController::class, 'getUserInfo']);
    Route::post('/addAddress', [GoodController::class, 'addAddress']);
    Route::get('/getAddress', [GoodController::class, 'getAddress']);
    Route::get('/removeAddress/{address}', [GoodController::class, 'removeAddress']);
    Route::post('/restockNotice', [GoodController::class, 'restockNotice']);
});

Route::middleware(['auth:sanctum', 'auth.signed:admin'])->group(function () {
    Route::prefix('admin')->group(function () {
        Route::post('/create', [CustomUserController::class, 'store']);
    });
    Route::prefix('post')->group(function () {
        Route::post('/category/create', [PostController::class, 'cateCreate']); //分類建立資料
        Route::post('/category/{id}/update', [PostController::class, 'cateUpdate']); //分類更新資料
        Route::post('/tag/create', [PostController::class, 'tagCreate']); //標籤建立資料
        Route::post('/tag/{id}/update', [PostController::class, 'tagUpdate']); //標籤更新資料
        Route::post('/{target}/deleteCheck', [PostController::class, 'freezeCheck']); //刪除分類、標籤時，確認有關連的文章
        Route::post('/{target}/delete', [PostController::class, 'freeze']); //刪除分類、標籤、文章
        // Route::post('/create', [PostController::class, 'postCreate']); //文章的建立or更新
        Route::post('/create/{action?}', [PostController::class, 'postCreate']); //文章的建立or更新
        Route::post('/upload', [PostController::class, 'makePicTemp']); //WangEditor用圖片上傳路徑
        Route::get('/{id}/getContent/', [PostController::class, 'getContent']); //獲得指定ID的文章內容與其相關的標籤
        Route::get('/{post}/beComplete/', [PostController::class, 'beComplete']); //將指定文章的狀態改變成正式發佈
        Route::get('/{post}/save', [PostController::class, 'saveForNext']); //將目前的文章編輯狀態儲存，但是不做發布
        Route::get('/{post}/release', [PostController::class, 'release']); //使用快捷方式發布文章
    });

    Route::prefix('album')->group(function () {
        Route::post('/create', [AlbumController::class, 'albumCreate']); //相簿建立資料
        Route::post('/{album}/edit', [AlbumController::class, 'albumUpdate']); //指定相簿更新資料
        Route::get('/list', [AlbumController::class, 'getList']); //顯示所有相簿的年月分類陣列資料
        Route::post('/uploadImg', [AlbumController::class, 'UploadImg']); //相簿內圖片上傳
        Route::post('/photo/{photo}/edit', [AlbumController::class, 'editImgInfo']); //相片資訊更新
        Route::post('/photo/{photo}/update', [AlbumController::class, 'changePhoto']); //相片更新
        Route::get('/{album}/delete', [AlbumController::class, 'freeze']);
        Route::post('photo/delete', [AlbumController::class, 'freezePhotos']);
        Route::get('/getList/{admin}', [AlbumController::class, 'getList']);
    });

    Route::prefix('pedia')->group(function () {
        Route::post('/content/create', [PediaController::class, 'contentCreate']); //
        Route::post('/gallery/create', [PediaController::class, 'galleryCreate']);
        Route::get('/gallery/{id}/delete', [PediaController::class, 'galleryDelete']);
        Route::post('/{target}/create', [PediaController::class, 'attrCreate']); //傳送資料至分類／標籤建立函式，建立對象由網址中的變數來控制
        Route::post('/{target}/{id}/update', [PediaController::class, 'attrUpdate']); //傳送資料至分類／標籤更新函式，更新對象由網址中的變數來控制
        Route::post('/{target}/delete', [PediaController::class, 'Freeze']); //傳送要凍結的分類／標籤／項目之陣列，凍結之對象由網址中的變數來控制
        Route::post('/create', [PediaController::class, 'itemCreate']); //傳送資料建立新的百科項目
        Route::post('/{oldId}/update', [PediaController::class, 'itemCreate']); //傳送資料更新舊的百科項目
        Route::post('/createRemark', [PediaController::class, 'createRemark']); //建立百科項目的註釋
    });

    Route::prefix('good')->group(function () {
        Route::post('/create', [GoodController::class, 'goodCreate']);
        Route::post('/{good}/update', [GoodController::class, 'goodCreate']);
        Route::get('/{good}/delete', [GoodController::class, 'goodDelete']);
        Route::get('/{good}/putdown', [GoodController::class, 'putDown']);
        Route::get('/{good}/putUp', [GoodController::class, 'putUp']);
        Route::post('/stockChange', [GoodController::class, 'stockChange']);
        Route::post('/category/create', [GoodController::class, 'categoryCreate']);
        Route::post('/category/{id}/update', [GoodController::class, 'categoryCreate']);
        Route::post('/category/delete', [GoodController::class, 'categoryDelete']);
        // Route::post('/addCart', [GoodController::class, 'addCart']);
        // Route::post('/cartChange', [GoodController::class, 'cartChange']);
        // Route::post('/order', [GoodController::class, 'orderCreate']);
        Route::get('/order/{serial}/{state}', [GoodController::class, 'orderChangeState']);
        Route::post('/order/{serial}/edit', [GoodController::class, 'orderEdit']);
    });
});

// Route::post('/post/upload', [PostController::class, 'makePicTemp']); //WangEditor用圖片上傳路徑
Route::post('post/user', [PostController::class, 'user']);
Route::get('/album/photo/{photo}', [AlbumController::class, 'getPhoto']); //回傳指定照片，公開
Route::get('/getAlbumList', [AlbumController::class, 'getList']);