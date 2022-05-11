<?php

use App\Http\Controllers\Api\AlbumController;
use App\Http\Controllers\Api\GoodController;
use App\Http\Controllers\Api\PediaController;
use App\Http\Controllers\Api\PostController;
use App\Mail\OrderCompleteMail;
use App\Models\Album;
use App\Models\Good;
use App\Models\GoodOrder;
use App\Models\GoodTag;
use App\Models\PediaCategory;
use App\Models\PediaTag;
use App\Models\Photo;
use App\Models\PostCategory;
use App\Models\PostTag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */
Route::view('/', 'working');

Route::get('/testArea', function () {
    return view('home');
})->name('home');

Route::view('/welcome', 'welcome');

Route::get('loginTest', function () {
    return 'login';
})->middleware(['auth:web']);

Route::get('/mailTest', function () {
    $order = GoodOrder::Where('id', '13')->first();
    $details = $order->getDetails;
    $payment = $order->getPayment;
    $user = $order->name;
    $serial = $order->serial;
    $freight = $order->freight;
    $total = $order->total;
    return (new OrderCompleteMail($user, $serial, $details, $freight, $total, $payment))->render();
});

Route::post('/test', function (Request $request) {
    return print_r($request);
})->name('test');

Route::get('/Auth/order/{order}', function (GoodOrder $order) {
    return view('good.orderShow', compact('order'));
})->name('order.auth')->middleware([
    'auth.signed:admin',
]);

Route::get('/clear', function () {
    $result = Cache::forget(Auth::id());
    dump($result);
});

Route::view('/unknown', 'post.unfound')->name('unknown');

//O是OPEN的O
Route::prefix('o')->group(function () {
    Route::get('/post-list', [PostController::class, 'showPostList']); //呼叫所有發布公開的文章頁面列表，完全開放
    Route::get('/post/category/{id}', [PostController::class, 'showByCategory']); //呼叫指定分類的文章列表，完全開放
    Route::get('/post/tag/{id}', [PostController::class, 'showByTag']); //呼叫包含指定標籤的文章列表，完全開放
    Route::get('/post/{id}', [PostController::class, 'show']); //呼叫指定文章頁面，完全開放
    Route::get('/pedia/{id}', [PediaController::class, 'itemShow']); //呼叫指定百科項目頁面，完全開放
    // Route::get('/album-list', [AlbumController::class, 'showAlbumList']); //顯示相簿列表，可加入年或年月搜尋
    // Route::get('/album-list/{year}', [AlbumController::class, 'showAlbumList']); //顯示相簿列表，可加入年或年月搜尋
    Route::get('/album-list/{year?}/{month?}', [AlbumController::class, 'showAlbumList']); //顯示相簿列表，可加入年或年月搜尋
    Route::get('/album/{album}/photos', [AlbumController::class, 'showPhotos']); //顯示指定相簿，全開放
    Route::get('/good-list/{category?}', [GoodController::class, 'showGoodList']); //
    Route::get('/good/{serial}', [GoodController::class, 'showGood']);
});

Route::middleware(['auth'])->group(function () {
    Route::get('/order/{serial}', [GoodController::class, 'showOrder']);
    Route::post('/addCart', [GoodController::class, 'showAddCart'])->name('addCart');
    Route::get('/order/addCart/{good}', function (Good $good) {
        return view('good.addCart', compact('good'));
    });
    Route::get('/orderCheck', [GoodController::class, 'callOrderCheck'])->name('orderCheck');
    Route::get('/order/{serial}/complete', [GoodController::class, 'orderComplete']);
    Route::get('/order-list/{start?}/{end?}/{page?}/{state?}', [GoodController::class, 'orderList']);
    // Route::get('/order-list', [GoodController::class, 'orderList']);
    Route::view('/changePassword', 'user.changePw');
    Route::view('/changeInfo', 'user.changeInfo');
    Route::get('/favorite-list', [GoodController::class, 'favoriteList']);
});

Route::middleware(['auth', 'auth.signed:admin'])->group(function () {
    Route::view('/dashboard', 'backendHome')->name('dashboard');
    Route::prefix('admin')->group(function () {
        Route::view('/create', 'user.create');
        Route::view('/changePassword', 'user.changePwBackend');
        Route::view('/changeInfo', 'user.changeInfoBackend');
    });
    Route::prefix('post')->group(function () {
        Route::get('/list', [PostController::class, 'postList']); //編輯模式呼叫文章列表
        Route::get('/list/{type}', [PostController::class, 'postList']); //編輯模式呼叫文章列表
        Route::get('/edit', [PostController::class, 'callPostEditor']); //建立新文章的頁面
        Route::get('/{post}/edit/{action}', [PostController::class, 'callPostUpdate']); //修改既有文章的頁面
        Route::get('/category/list', [PostController::class, 'postCategoryList']); //分類一覽
        Route::view('/category/edit', 'post.cateEditor'); //建立分類的頁面
        Route::get('/category/{cate}/edit', function (PostCategory $cate) {return view('post.cateEditor', compact('cate'));}); //修改既有分類的頁面
        Route::get('/tag/list', [PostController::class, 'postTagList']); //標籤一覽
        Route::view('/tag/edit', 'post.tagEditor'); //建立標籤的頁面
        Route::get('/tag/{tag}/edit', function (PostTag $tag) {return view('post.tagEditor', compact('tag'));}); //修改既有標籤的頁面
        Route::get('/{id}/preview', [PostController::class, 'preview']); //呼叫指定文章發佈前的預覽
    });

    Route::prefix('album')->group(function () {
        Route::get('/list/{year?}/{month?}', [AlbumController::class, 'albumList']);
        Route::view('/edit', 'album.albumEditor'); //建立相簿的頁面
        Route::get('/{album}/edit', function (Album $album) {return view('album.albumEditor', compact('album'));}); //修改既有相簿的頁面
        Route::get('/{album}/photos/edit', function (Album $album) {return view('album.photoUpload', compact('album'));}); //針對指定ID的相簿上傳相片/影片的頁面
        Route::get('/{album}/photos', [AlbumController::class, 'showPhotosForEdit']); //顯示指定ID的相簿的相片一覽及編輯頁面
        Route::get('/album/{year}/{month}', [AlbumController::class, 'showAlbums']); //顯示指定年月份的所有相簿
        Route::get('/photo/{photo}/edit', function (Photo $photo) {return view('album.photoEditor', compact('photo'));}); //進入指定ID的相片or影片編輯資訊
    });

    Route::prefix('pedia')->group(function () {
        Route::view('/category/edit', 'pedia.cateEditor'); //呼叫建立百科分類的頁面
        Route::view('/tag/edit', 'pedia.tagEditor'); //呼叫建立百科標籤的頁面
        Route::get('/category/{cate}/edit', function (PediaCategory $cate) {return view('pedia.cateEditor', compact('cate'));}); //呼叫分類編輯頁面並傳入指定分類的資料
        Route::get('/tag/{tag}/edit', function (PediaTag $tag) {return view('pedia.tagEditor', compact('tag'));}); //呼叫標籤編輯頁面並傳入指定標籤的資料
        Route::get('/edit', [PediaController::class, 'callItemEditor']); //呼叫新建百科項目的頁面
        Route::get('/{id}/preview', [PediaController::class, 'preview']); //呼叫項目預覽及內容增減頁面
        Route::get('/{fatherId}/item/edit/{id?}', [PediaController::class, 'callItemEditor']); //項目修改
        Route::get('/{fatherId}/content/edit/{id?}', [PediaController::class, 'callContentEditor']); //項目內容編輯
        Route::get('/{fatherId}/gallery/edit/', [PediaController::class, 'callGalleryEditor']); //畫廊編輯頁面
        Route::get('/{id}/{action}', [PediaController::class, 'itemShow']); //呼叫指定百科項目的編輯頁面
    });
    Route::prefix('good')->group(function () {
        Route::get('/category/create', [GoodController::class, 'callCategoryEditor']);
        Route::get('/category/{id}/edit', [GoodController::class, 'callCategoryEditor']);
        Route::get('/category/list', [GoodController::class, 'categoryList']);
        Route::get('/tag/list', [GoodController::class, 'tagList']);
        Route::view('/tag/edit', 'good.tagEditor');
        Route::get('/tag/{tag}/edit', function (GoodTag $tag) {return view('good.tagEditor', compact('tag'));});
        Route::get('/create', [GoodController::class, 'callGoodEditor']);
        Route::get('/{id}/edit', [GoodController::class, 'callGoodEditor']);
        Route::get('/list/{category?}', [GoodController::class, 'goodList']);
        Route::get('/{good}/stock', [GoodController::class, 'goodStock']);
        Route::get('/order/list/{start?}/{end?}/{page?}/{state?}', [GoodController::class, 'orderListAdmin']);
        Route::get('/order/{serial}/edit', [GoodController::class, 'callOrderEditor']);
        Route::get('/order/{serial}', [GoodController::class, 'showOrderAdmin']);

    });
});

require __DIR__ . '/auth.php';