<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\OrderCompleteMail;
use App\Mail\RestockNoticeMail;
use App\Models\Good;
use App\Models\GoodCategory;
use App\Models\GoodDetail;
use App\Models\GoodFavorite;
use App\Models\GoodOrder;
use App\Models\GoodOrderPayment;
use App\Models\GoodOrderState;
use App\Models\GoodStock;
use App\Models\GoodTag;
use App\Models\GoodType;
use App\Models\RestockNotice;
use App\Models\Tag_for_good;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class GoodController extends Controller
{
    private function makeJson($state, $data = null, $msg = null)
    {
        return response()->json(['state' => $state, 'data' => $data, 'msg' => $msg])->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }

    public function callGoodEditor($good = null)
    {
        $categories = GoodCategory::Where('state', 1)->get();
        $tags = GoodTag::get();
        if (is_null($good)) {
            return view('good.goodEditor', compact('categories', 'tags'));
        } else {
            $good = Good::Where('serial', $good)->first();
            $tagForGood = Tag_for_good::Where('goodId', $good->id)->get();
            return view('good.goodEditor', compact('categories', 'good', 'tags', 'tagForGood'));
        }
    }

    public function goodCreate(Good $good = null, Request $request)
    {
        try {
            // return $this->makeJson(0, gettype($good->gallery), 'FIRE');
            if ($request->name == '' || is_null($request->name)) {
                return $this->makeJson(0, null, 'NO_GOOD_NAME');
            }

            if ($request->category == '' || is_null($request->category)) {
                return $this->makeJson(0, null, 'NO_GOOD_CATEGORY');
            }

            $update = false;
            $params = $request->only('name', 'caption', 'category', 'hot');

            if ($request->caption == '' || is_null($request->caption)) {
                $params['caption'] = '????????????';
            }

            if (is_null($good)) {
                // return $this->makeJson(0, $params, null);
                $result = Good::create($params);
                if ($result->id == '') {
                    return $this->makeJson(0, $result, 'GOOD_CREATE_ERROR');
                }
                $good = $result;
            } else {
                $result = $good->update($params);
                if (!$result) {
                    return $this->makeJson(0, $result, 'GOOD_UPDATE_ERROR');
                }
                $update = true;
            }

            $id = $good->id;
            $good = Good::Where('id', $id)->get()->first();
            $path = '/storage/goods/' . $id;
            $savePath = '/public/goods/' . $id;
            $galleryList = explode(',', $request->galleries);

            // return $this->makeJson(0, $update, null);

            if (!is_null($request->cover)) {
                if ($update) {
                    $targetCover = str_replace('storage', 'public', $good->cover);
                    if (Storage::exists($targetCover)) {
                        $result = Storage::delete($targetCover);
                        if (!$result) {
                            return $this->makeJson(0, $result, 'OLD_COVER_DELETE_ERROR');
                        }

                    }
                }
                $file = str_replace('data:image/png;base64,', '', $request->cover);
                $file = str_replace(' ', '+', $file);
                $file = base64_decode($file);
                $filename = uniqid('cover', false) . '.png';
                $result = Storage::put($savePath . '/' . $filename, $file);
                if (!$result) {
                    return $this->makeJson(0, $result, 'GOOD_COVER_SAVE_ERROR');
                }
                $result = $good->update(['cover' => $path . '/' . $filename]);
                if (!$result) {
                    return $this->makeJson(0, $result, 'GOOD_COVER_INSERT_ERROR');
                }
            }
            $deleteGallery = $request->deleteGallery; //???????????????????????????????????????
            if ($update) {
                $gallery = $good->gallery; //?????????????????????????????????
            } else {
                $gallery = array(); //????????????????????????
            }
            if ($update) {
                if (!is_null($deleteGallery) && $deleteGallery != "") {
                    //??????????????????????????????null
                    $deleteGallery = explode(',', $request->deleteGallery); //?????????????????????
                    $temp = array();
                    $old = $good->gallery; //???????????????
                    for ($i = 0; $i < count($old); $i++) {
                        $needSave = true;
                        for ($j = 0; $j < count($deleteGallery); $j++) {
                            if ($old[$i] == $deleteGallery[$j]) { //???????????????????????????????????????????????????
                                $de = str_replace('storage', 'public', $deleteGallery[$j]); //???????????????????????????
                                if (Storage::exists($de)) { //????????????????????????????????????
                                    $result = Storage::delete($de);
                                    if (!$result) {
                                        return $this->makeJson(0, $result, 'DELETE_OLD_IMAGE_ERROR');
                                    }
                                }
                                $needSave = false;
                                break; //?????????????????????
                            }
                        }
                        if ($needSave) {
                            array_push($temp, $old[$i]);
                        }
                    }
                    $gallery = $temp;
                }
            }

            $galleries = $request->galleries;
            if (!is_null($galleries) && $galleries != "") {
                $galleries = explode(',', $galleries);
                foreach ($galleries as $g) {
                    $tg = str_replace('.', '_', $g);
                    if ($request->hasFile($tg)) {
                        $file = $request->file($tg);
                        $filename = $g;
                        $result = Storage::putFileAs($savePath, $file, $filename);
                        if (!$result) {
                            return $this->makeJson(0, $result, 'GALLERY_SAVE_ERROR');
                        }
                        array_push($gallery, $path . '/' . $filename);
                    }
                }
            }

            $gallery = base64_encode(serialize($gallery));

            $result = $good->update(['gallery' => $gallery]);
            if (!$result) {
                return $this->makeJson(0, $result, 'GALLERY_INSERT_ERROR');
            }
            $tags = $request->tags;
            if (!is_null($tags) && $tags != '') {
                if ($update) {
                    $deleteTags = explode(',', $request->deleteTags);
                    if (!is_null($deleteTags) && $deleteTags != '') {
                        foreach ($deleteTags as $d) {
                            $result = Tag_for_Good::Where('id', $d)->first();
                            if (!is_null($result) && $result != '') {
                                $result = $result->delete();
                                if (!$result) {
                                    return $this->makeJson(0, $result, 'TAG_DELETE_ERROR');
                                }
                            }
                        }
                    }
                }
                $tags = explode(',', $tags);
                foreach ($tags as $tag) {
                    $result = Tag_for_good::create(['goodId' => $id, 'tagId' => $tag]);
                    if (!$result) {
                        return $this->makeJson(0, $result, 'TAG_CREATE_ERROR');
                    }
                }
            }

            $count = 0;
            $list = array();
            $temp = explode(',', $request->typeList);
            foreach ($temp as $t) {
                $list[$count] = explode(',', $request[$t]);
                $count++;
            }

            $typeList = explode(',', $request->typeList);
            foreach ($typeList as $type) {
                $temp = explode(',', $request[$type]);
                $typeParams = array();
                $typeParams['goodId'] = $id;
                $typeParams['type'] = $temp[0];
                $typeParams['name'] = $temp[1];
                $typeParams['description'] = $temp[2];
                $typeParams['price'] = $temp[3];
                if (!$update) {
                    $result = GoodType::create($typeParams);
                    if (!$result) {
                        return $this->makeJson(0, $result, 'GOOD_TYPE_CREATE_ERROR');
                    }
                    $stockParams['goodId'] = $id;
                    $stockParams['goodType'] = $temp[0];
                    $stockParams['import'] = $temp[4];
                    $result = GoodStock::create($stockParams);
                    if (!$result) {
                        return $this->makeJson(0, $result, 'GOOD_STOCK_CREATE_ERROR');
                    }
                } else {
                    $deleteType = $request->deleteType;
                    if (!is_null($deleteType) && $deleteType != '') {
                        $deleteType = explode(',', $deleteType);
                        foreach ($deleteType as $value) {
                            $value = str_replace('type', '', $value);
                            $result = GoodType::Where('goodId', $id)->Where('type', $value)->get()->first();
                            if (!is_null($result)) {
                                $result = $result->delete();
                                if (!$result) {
                                    return $this->makeJson(0, $result, 'OLD_TYPE_DELETE_ERROR');
                                }
                                $result = GoodStock::Where('goodId', $id)->Where('goodType', $value)->get()->delete();
                                if (!$result) {
                                    return $this->makeJson(0, $result, 'OLD_TYPE_STOCK_DELETE_ERROR');
                                }
                            }
                        }
                    }
                    // $result = GoodType::Where('goodId', $id)->Where('type', $temp[0])->get()->first();
                    $result = GoodType::Where('goodId', $id)->Where('type', $temp[0])->get()->first();
                    if (is_null($result)) {
                        $result = GoodType::create($typeParams);
                        if (!$result) {
                            return $this->makeJson(0, $result, 'TYPE_CREATE_ERROR');
                        }
                        $result = GoodStock::create(['goodId' => $id, 'goodType' => $temp[0], 'import' => $temp[4]]);
                        if (!$result) {
                            return $this->makeJson(0, $result, 'STOCK_CREATE_ERROR');
                        }
                    } else {
                        $result = $result->update($typeParams);
                    }
                    // return $this->makeJson(0, $result, 'HERE?');
                    if (!$result) {
                        return $this->makeJson(0, $result, 'GOOD_TYPE_UPDATE_ERROR');
                    }
                }
            }
            return $this->makeJson(1, $id, null);
        } catch (\Exception $th) {
            return $this->makeJson(0, $th->getMessage(), $th->getCode());
        }
    }

    public function putdown($id)
    {
        $id = Good::Where('serial', $id)->first();
        $result = $id->update(['state' => 0]);
        if (!$result) {
            return $this->makeJson(0, $result, 'GOOD_PUTDOWN_ERROR');
        } else {
            return $this->makeJson(1, null, null);
        }
    }

    public function putUp($id)
    {
        $id = Good::Where('serial', $id)->first();
        $result = $id->update(['state' => 1]);
        if (!$result) {
            return $this->makeJson(0, $result, 'GOOD_PUT_UP_ERROR');
        } else {
            return $this->makeJson(1, null, null);
        }
    }

    public function goodDelete($id)
    {
        $good = Good::Where('serial', $id)->first();
        $tags = Tag_for_good::Where('goodId', $good->id)->get();
        foreach ($tags as $t) {
            $result = $t->delete();
            if (!$result) {
                return $this->makeJson(0, $result, 'GOOD_TAG_ERROR');
            }
        }
        $result = $good->delete();
        if (!$result) {
            return $this->makeJson(0, $result, 'GOOD_DELETE_ERROR');
        } else {
            return $this->makeJson(1, null, null);
        }
    }
    // ????????????????????????
    public function goodList($category = null)
    {
        $goods = Good::Where('state', 1)->orWhere('state', 0);
        if (!is_null($category)) {
            $goods = $goods->Where('category', $category);
        }
        $goods = $goods->paginate(12);
        return view('good.editList', compact('goods', 'category'));
    }
    //????????????????????????
    public function showGoodList($category = null)
    {
        $goods = Good::Where('state', 1);
        if (!is_null($category)) {
            $sub = GoodCategory::Where('sub', $category)->get();
            $goods = $goods->Where('category', $category);
            if (count($sub) > 0) {
                foreach ($sub as $s) {
                    $goods = $goods->orWhere('category', $s['id']);
                }
            }
        }
        $goods = $goods->paginate(6);
        return view('good.list', compact('goods', 'category'));
    }

    public function showGood($serial)
    {
        $good = Good::Where('serial', $serial)->first();
        $tags = Tag_for_good::Where('goodId', $good->id)->get();
        $tagList = array();
        for ($i = 0; $i < count($tags); $i++) {
            $tagList[$i] = $tags[$i]->tagId;
        }
        $list = null;
        $goodList = array();
        if (count($tagList) > 0) {
            $list = Tag_for_good::Select('goodId');
            for ($i = 0; $i < count($tagList); $i++) {
                if ($i == 0) {
                    $list = $list->Where('tagId', $tagList[$i])->Where('goodId', '!=', $good->id);
                } else {
                    $list = $list->orWhere('tagId', $tagList[$i])->Where('goodId', '!=', $good->id);
                }
            }
            $list = $list->distinct()->get();
            $index = array();
            for ($i = 0; $i < count($list); $i++) {
                $index[$i] = $list[$i];
            }
            if (count($index) > 4) {
                $index = array_rand($index, 5);
                for ($i = 0; $i < 5; $i++) {
                    $goodList[$i] = $list[$index[$i]];
                }
            } else {
                $goodList = $index;
            }
        }
        // return $goodList;

        if (is_null($good)) {
            return view('good.unknown');
        } else {
            return view('good.show', compact('good', 'tags', 'goodList'));
        }
    }

    public function showAddCart(Request $request)
    {
        $id = $request->id;
        $good = Good::Where('id', $id)->first();
        return view('good.addCart', compact('good'));
    }

    public function goodStock($good)
    {
        $good = Good::Where('serial', $good)->first();
        $types = GoodType::Where('goodId', $good->id)->get();
        return view('good.stockChange', compact('good', 'types'));
    }

    public function stockChange(Request $request)
    {
        $types = $request->types;
        $restock = false;
        $goodId = '';
        $typeList = array();
        foreach ($types as $t) {
            $params = array();
            if ($t[2] != 0) {
                $goodId = $t[0];
                $params['goodId'] = $t[0];
                $params['goodType'] = $t[1];
                if ($t[3] == 'true') {
                    $params['import'] = $t[2];
                    $stock = (GoodStock::Select('stock')->Where('goodId', $t[0])->Where('goodType', $t[1])->orderBy('created_at', 'DESC')->first())['stock'];
                    if ($stock == 0) {
                        $restock = true;
                        array_push($typeList, $t[1]);
                    }
                } else {
                    $params['export'] = $t[2];
                }
                $result = GoodStock::create($params);
                if ($result->id == '') {
                    return $this->makeJson(0, $result, 'STOCK_CREATE_ERROR');
                }
            }
        }
        if ($restock) {
            $this->sendRestockNotice($goodId, $typeList);
        }

        return $this->makeJson(1, null, $result);
    }

    public function sendRestockNotice($good, $typeList)
    {
        // $stock = GoodStock::Where('goodId', $good)->first();
        // $stock = $stock['stock'];
        $type = array();
        $t = Good::Select('name', 'cover', 'serial')->Where('id', $good)->first();
        foreach ($typeList as $value) {
            $temp = GoodType::Select('name')->Where('goodId', $good)->Where('type', $value)->first();
            array_push($type, $temp['name']);
        }
        $goodName = $t->name;
        $cover = $t->cover;
        $serial = $t->serial;
        $list = RestockNotice::Where('goodId', $good)->get();
        foreach ($list as $l) {
            $user = $l->getUser;
            $mail = $user->email;
            $name = $user->name;
            $to = [
                ['email' => $mail, 'name' => $name],
            ]
            ;
            Mail::to($to)->send(new RestockNoticeMail($name, $serial, $goodName, $cover, $type));
        }
    }

    public function callCategoryEditor(GoodCategory $id = null)
    {
        $categories = GoodCategory::Where('state', 1)->Where('sub', 0)->get();
        if (is_null($id)) {
            return view('good.categoryEditor', compact('categories'));
        } else {
            return view('good.categoryEditor', compact('id', 'categories'));
        }
    }

    public function categoryCreate(GoodCategory $id = null, Request $request)
    {
        $params = $request->only('name', 'content', 'state', 'sub');
        if (is_null($id)) {
            $result = GoodCategory::create($params);
        } else {
            $result = $id->update($params);
        }
        if (!$result) {
            if (is_null($id)) {
                return $this->makeJson(0, $result, 'GOOD_CATEGORY_CREATE_ERROR');
            } else {
                return $this->makeJson(0, $result, 'GOOD_CATEGORY_UPDATE_ERROR');
            }
        } else {
            return $this->makeJson(1, null, null);
        }
    }

    public function categoryDelete(Request $request)
    {
        $data = $request->id;
        if (gettype($data) == 'string') {
            $result = GoodCategory::Where('id', $data)->get()->first()->delete();
            if (!$result) {
                return $this->makeJson(0, $result, 'CATEGORY_DELETE_ERROR');
            }
        } else if (gettype($data) == 'array') {
            foreach ($data as $d) {
                $result = GoodCategory::Where('id', $d)->get()->first()->delete();
                if (!$result) {
                    return $this->makeJson(0, $result, 'CATEGORY_DELETE_ERROR');
                }
            }
        }

        return $this->makeJson(1, null, null);

    }

    public function categoryShow(GoodCategory $id)
    {
        // $goods = Good::Where('category', $id)->get();
        // if ($goods->count() > 0) {
        //     return $this->makeJson(1, $goods, null);
        // } else {
        //     return $this->makeJson(0, $goods, 'NO_DATA');
        // }
    }

    public function categoryList()
    {
        $categories = GoodCategory::get();
        return view('good.categoryList', compact('categories'));
    }

    public function orderListAdmin($start = null, $end = null, $page = null, $state = null)
    {
        $orders = $this->getOrderList('admin', $start, $end, $page, $state);
        $status = GoodOrderState::get();
        return view('good.orderListBackend', compact('orders', 'status', 'state', 'start', 'end', 'page'));
    }

    public function orderList($start = null, $end = null, $page = null, $state = null)
    {
        $orders = $this->getOrderList('user', $start, $end, $page, $state);
        $status = GoodOrderState::get();
        return view('good.orderList', compact('orders', 'status', 'state', 'start', 'end', 'page'));
    }

    private function getOrderList($type, $start = null, $end = null, $page = null, $state = null)
    {
        $order = GoodOrder::Select('*');

        if ($type == 'user') {
            $user = Auth::id();
            $order->where('userId', $user);
        }

        if (!is_null($state) && $state != 'all') {
            $order = $order->where('state', $state);
        }

        if (!is_null($start) && $start != 'null' && !is_null($end) && $end != 'null') {
            if ($start <= $end) {
                $order = $order->where('created_at', '>=', $start . ' 00:00:00')->where('created_at', '<=', $end . ' 23:59:59');
                // $order = $order->where('created_at', '>=', '2022-03-23 00:00:00')->where('created_at', '<=', '2022-03-25 23:59:59');
            } else {
                return $this->makeJson(0, null, 'START_BIG_THAN_END');
            }
        }

        $order = $order->orderBy('created_at', 'desc');

        // if ($request->has('sort')) {
        //     if ($request->sort == 'ASC') {
        //         $order = $order->orderBy('created_at', 'asc');
        //     } else {
        //         $order = $order->orderBy('created_at', 'desc');
        //     }
        // }

        if (is_null($page)) {
            $page = 15;
        }

        return $order->paginate($page);

    }

    public function showOrderAdmin($serial)
    {
        $order = $this->getOrder('admin', $serial);

        return view('good.orderShowBackend', compact('order'));
    }

    public function showOrder($serial)
    {
        $order = $this->getOrder($serial);
        if (is_null($order)) {
            return view('post.unfound');
        } else {
            return view('good.orderShow', compact('order'));
        }
    }

    private function getOrder($serial)
    {
        $order = GoodOrder::where('serial', $serial);
        if (Auth::user()->Auth == 'user') {
            $user = Auth::id();
            $order = $order->where('userId', $user);
        }
        $order = $order->first();
        return $order;
    }

    public function callOrderEditor($serial)
    {
        $order = GoodOrder::where('serial', $serial)->Where('state', 1)->first();
        $status = GoodOrderState::get();
        $payments = GoodOrderPayment::get();
        if (is_null($order)) {
            return view('good.unknown');
        }
        return view('good.orderEditor', compact('order', 'status', 'payments'));
    }

    public function orderEdit($serial, Request $request)
    {
        $order = GoodOrder::where('serial', $serial)->Where('state', 1)->orWhere('state', 2)->orWhere('state', 3)->first();
        $params = $request->only('name', 'tel', 'address', 'freight', 'total', 'pay', 'receiptType', 'taxNumber', 'receiptSendType', 'receiptZipcode', 'receiptAddress', 'state');
        // return $this->makeJson(0, $params, null);
        $result = $order->update($params);
        if (!$result) {
            return $this->makeJson(0, $result, 'ORDER_UPDATE_ERROR');
        }
        $types = $request->types;
        if (!is_null($types) && count($types) > 0) {
            foreach ($types as $v) {
                $result = GoodDetail::Where('id', $v[0])->first()->update(['amount' => $v[1], 'quantity' => $v[2]]);
                if (!$result) {
                    return $this->makeJson(0, $result, 'DETAIL_UPDATE_ERROR');
                }
            }
        }

        $deleteType = $request->deleteType;
        if (!is_null($deleteType) && count($deleteType) > 0) {
            foreach ($deleteType as $d) {
                $result = GoodDetail::Where('id', $d)->first()->delete();
                if (!$result) {
                    return $this->makeJson(0, $result, 'DETAIL_DELETE_ERROR');
                }
            }
        }

        return $this->makeJson(1, null, null);
    }

    public function orderChangeState($serial, $state)
    {
        $order = GoodOrder::where('serial', $serial)->first();

        switch ($state) {
            case 'paid':
                $result = $order->update(['state' => 2]);
                break;
            case 'delivered':
                $result = $order->update(['state' => 3]);
                break;
            case 'cancel':
                $result = $order->update(['state' => 0]);
                break;
        }
        if (!$result) {
            return $this->makeJson(0, $result, 'ORDER_BE_OVER_ERROR');
        }
        return $this->makeJson(1, null, null);
    }

    public function addCart(Request $request)
    {
        $id = $request->id;
        $orders = $request->orders;
        $update = false;
        $user = 'good' . Auth::id();
        if (Cache::has($user)) {
            $orderParams = Cache::get($user);
            $update = true;
        } else {
            $orderParams = array();
        }
        foreach ($orders as $order) {
            $exist = false;
            $number = intval($order[1]);
            if ($number > 0) {
                if ($update) {
                    foreach ($orderParams as $key => $old) {
                        if ($old[0] == $id && $old[1] == $order[0]) {
                            $oldQuantity = intval($old[4]);
                            $orderParams[$key][4] = $oldQuantity + $number;
                            // $old['3'] .= $number; //???????????????
                            $exist = true;
                            break;
                        }
                    }
                }
                if (!$exist) {
                    $type = $order[0];
                    $name = (Good::Where('id', $id)->first())['name'];
                    $data = GoodType::Select('name', 'price')->Where('goodId', $id)->Where('type', $type)->first();
                    $typeName = $data['name'];
                    $price = intval($data['price']);
                    array_push($orderParams, [$id, $type, $name, $typeName, $number, $price]);
                }
            }
        }
        $result = Cache::put($user, $orderParams);
        if (!$result) {
            return $this->makeJson(0, $result, 'CACHE_SAVE_ERROR');
        }
        return $this->makeJson(1, null, null);
    }

    public function cartChange(Request $request)
    {
        if (Auth::check()) {
            $id = 'good' . Auth::id();
            $list = Cache::get($id);
            $action = null;
            $index = $request->index;
            unset($list[$index]);
            if (count($list) == 0) {
                $result = Cache::forget($id);
                $action = 'reload';
            } else {
                $result = Cache::put($id, $list);
            }
            if (!$result) {
                return $this->makeJson(0, $result, 'CACHE_SAVE_ERROR');
            } else {
                return $this->makeJson(1, $action, null);
            }
        } else {
            return $this->makeJson(0, null, 'NO_USER_LOGIN');
        }
    }

    public function callOrderCheck()
    {
        $payments = GoodOrderPayment::get();
        return view('good.check', compact('payments'));
    }

    public function getUserInfo()
    {
        $name = Auth::user()->name;
        $tel = Auth::user()->tel;
        return $this->makeJson(1, ['name' => $name, 'tel' => $tel], null);
    }

    public function addAddress(Request $request)
    {
        $params = $request->only('zipcode', 'address');
        $params['userId'] = Auth::id();
        $result = UserAddress::create($params);
        if ($result->id == '') {
            return $this->makeJson(0, $result, 'SAVE_ADDRESS_ERROR');
        } else {
            return $this->makeJson(1, null, null);
        }
    }

    public function getAddress()
    {
        $user = Auth::id();
        $addresses = UserAddress::Where('userId', $user)->get();
        if (count($addresses) == 0 || is_null($addresses)) {
            return $this->makeJson(0, null, 'NO_ADDRESS_DATA');
        } else {
            return $this->makeJson(1, $addresses, null);
        }
    }

    public function removeAddress(UserAddress $address)
    {
        if (is_null($address)) {
            return $this->makeJson(0, null, 'NO_DELETE_TARGET');
        } else {
            $result = $address->delete();
            if (!$result) {
                return $this->makeJson(0, $result, 'DELETE_ADDRESS_ERROR');
            }
            return $this->makeJson(1, null, null);
        }
    }

    public function orderCreate($serial = null, Request $request)
    {
        if (!$request->has('name') || $request->name == '') {
            return $this->makeJson(0, null, 'NO_NAME');
        }

        if (!$request->has('tel') || $request->tel == '') {
            return $this->makeJson(0, null, 'NO_TEL');
        }

        //??????????????????????????????????????????????????????????????????
        if ($request->pay != '2') {
            if (!$request->has('zipcode') || $request->zipcode == '') {
                return $this->makeJson(0, null, 'NO_ZIPCODE');
            }

            if (!$request->has('address') || $request->address == '') {
                return $this->makeJson(0, null, 'NO_ADDRESS');
            }

            if (!$request->has('invoiceType') || $request->invoiceType == '') {
                return $this->makeJson(0, null, 'NO_INVOICE_TYPE');
            }

            //??????????????????????????????????????????
            if ($request->invoiceType == 'triplePart') {
                if (!$request->has('taxNumber') || $request->taxNumber == '') {
                    return $this->makeJson(0, null, 'NO_TAX_NUMBER');
                }
            }

            if (!$request->has('invoiceSendType') || $request->invoiceSendType == '') {
                return $this->makeJson(0, null, 'NO_INVOICE_SEND_TYPE');
            }

            //???????????????????????????????????????????????????????????????
            if ($request->invoiceSendType == 'another') {
                if (!$request->has('invoiceZipcode') || $request->invoiceZipcode == '') {
                    return $this->makeJson(0, null, 'NO_INVOICE_SEND_ZIPCODE');
                }
                if (!$request->has('invoiceAddress') || $request->invoiceAddress == '') {
                    return $this->makeJson(0, null, 'NO_INVOICE_SEND_ADDRESS');
                }
            }

        }

        $orderParams = $request->only('name', 'tel', 'zipcode', 'address', 'pay', 'invoiceType', 'taxNumber', 'invoiceSendType', 'invoiceZipcode', 'invoiceAddress', 'freight', 'remark');
        if ($request->invoiceType == 'donate') {
            $orderParams['invoiceSendType'] = 'no';
        }
        // return $this->makeJson(0, $orderParams, null);
        $orderParams['userId'] = Auth::id();
        $total = 0;
        $orderParams['total'] = $total;
        $orderParams['state'] = 1;
        if ($request->pay == '2') {
            $orderParams['address'] = '-';
        }
        $result = GoodOrder::create($orderParams);
        if ($result->id == '') {
            return $this->makeJson(0, $result, 'ORDER_CREATE_ERROR');
        }
        $id = $result->id;
        $good = GoodOrder::Where('id', $id)->first();
        $serial = $good->serial;
        // $createTime = substr($good->created_at, 0, 18);
        $details = Cache::get('good' . Auth::id());
        $createdDetails = array();
        $createdStocks = array();
        foreach ($details as $d) {
            $temp = array();
            $temp['orderId'] = $id;
            $temp['goodId'] = $d[0];
            $temp['type'] = $d[1];
            $temp['quantity'] = $d[4];
            $temp['amount'] = $d[5];
            $total += intval($d[4]) * intval($d[5]);
            $stockCheck = (GoodStock::Where('goodId', $d[0])->Where('goodType', $d[1])->orderBy('updated_at', 'desc')->limit(1)->first())['stock'];
            if ($stockCheck == 0) {
                $result = $good->delete();
                for ($i = 0; $i < count($createdDetails); $i++) {
                    $result = GoodDetail::Where('id', $createdDetails[$i])->first()->delete();
                    $result = GoodStock::Where('id', $createdStocks[$i])->first()->delete();
                }
                return $this->makeJson(0, $d[2] . '-' . $d[3], 'STOCK_IS_ZERO');
            } else if ($stockCheck < $d[4]) {
                $result = $good->delete();
                for ($i = 0; $i < count($createdDetails); $i++) {
                    $result = GoodDetail::Where('id', $createdDetails[$i])->first()->delete();
                    $result = GoodStock::Where('id', $createdStocks[$i])->first()->delete();
                }
                return $this->makeJson(0, $d[2] . '-' . $d[3], 'MORE_THAN_STOCK');
            } else {
                $result = GoodDetail::create($temp);
                if ($result->id == '') {
                    return $this->makeJson(0, $result, 'DETAIL_CREATE_ERROR');
                } else {
                    array_push($createdDetails, $result->id);
                }
                $stockChange = GoodStock::create([
                    'goodId' => $d[0],
                    'goodType' => $d[1],
                    'export' => $d[4],
                ]);
                if ($stockChange->id == '') {
                    return $this->makeJson(0, $stockChange, 'STOCK_CHANGE_ERROR');
                } else {
                    array_push($createdStocks, $stockChange->id);
                }
            }
        }
        $result = $good->update(['total' => $total]);
        if (!$result) {
            return $this->makeJson(0, $result, 'TOTAL_INSERT_ERROR');
        }
        Cache::forget('good' . Auth::id());
        $order = GoodOrder::Where('serial', $serial)->first();
        $this->sendOrderCompleteMail($order);
        return $this->makeJson(1, $serial, null);
    }

    public function orderComplete($serial)
    {
        $order = GoodOrder::Where('serial', $serial)->first();
        // $this->sendOrderCompleteMail($order);
        return view('good.complete', compact('order'));
    }

    private function sendOrderCompleteMail($order)
    {
        $name = Auth::user()->name;
        $email = Auth::user()->email;
        $details = $order->getDetails;
        $payment = $order->getPayment;
        $user = $order->name;
        $serial = $order->serial;
        $freight = $order->freight;
        $total = $order->total;
        $to = [
            [
                'name' => $user,
                'email' => $email,
            ],
        ];
        Mail::to($to)->send(new OrderCompleteMail($user, $serial, $details, $freight, $total, $payment));
    }

    public function orderCancel(Request $request)
    {
        if (Auth::user()->Auth == 'user') {
            $serial = $request->serial;
            $user = Auth::id();
            $result = GoodOrder::Where('serial', $serial)->Where('userId', $user)->first();
            if (is_null($result)) {
                return $this->makeJson(0, null, 'ORDER_NOT_FOUND');
            } else {
                $id = $result->id;
                $result = $result->update(['state' => 0]);
                $details = GoodDetail::Where('orderId', $id)->get();
                if (count($details) > 0) {
                    foreach ($details as $d) {
                        $result = GoodStock::create([
                            'goodId' => $d->goodId,
                            'goodType' => $d->type,
                            'import' => $d->quantity,
                        ]);
                    }
                }
                if (!$result) {
                    return $this->makeJson(0, $result, 'ORDER_CANCEL_ERROR');
                } else {
                    return $this->makeJson(1, null, null);
                }
            }
        }
    }

    public function changeOrder(GoodOrder $order, Request $request)
    {
        $params = $request->only('name', 'tel', 'zipcode', 'address', 'invoiceType', 'taxNumber', 'invoiceSendType', 'invoiceZipcode', 'invoiceAddress', 'pay', 'freight', 'remark');
        return $this->makeJson(0, $params, null);
        $result = $order->update($params);
        $id = $order->id;
        if (!$result) {
            return $this->makeJson(0, $result, 'ORDER_UPDATE_ERROR');
        }

        $changeTemp = $request->changeList;
        $changeList = array();
        $temp = array();
        $count = 0;
        foreach ($changeTemp as $ct) {
            array_push($temp, $ct);
            $count++;
            if ($count == 2) {
                array_push($changeList, $temp);
                $temp = array();
                $count = 0;
            }
        }

        foreach ($changeList as $c) {
            $detailId = $c[0];
            $params = $request[$c[0]];
            switch ($c['1']) {
                case 'add':
                    $params['orderId'] = $id;
                    $result = GoodDetail::create($params);
                    if ($result->id == '') {
                        return $this->makeJson(0, $result, 'DETAIL_CREATE_ERROR');
                    }
                    break;
                case 'change':
                    $result = GoodDetail::Where('id', $detailId)->update($params);
                    if (!$result) {
                        return $this->makeJson(0, $result, 'DETAIL_UPDATE_ERROR');
                    }
                    break;
                case 'delete':
                    $result = GoodDetail::Where('id', $detailId)->delete();
                    if (!$result) {
                        return $this->makeJson(0, $result, 'DETAIL_DELETE_ERROR');
                    }
                    break;
            }
        }
        return $this->makeJson(1, null, null);
    }

    public function orderVoid(GoodOrder $order, Request $request)
    {
        $result = $order->update(['state' => $request->state]);
        if (!$result) {
            return $this->makeJson(0, $result, 'ORDER_VOID_ERROR');
        } else {
            return $this->makeJson(1, null, null);
        }
    }

    public function reportPaid(Request $request)
    {
        $order = GoodOrder::Where('serial', $request->serial)->first();
        $params['payName'] = $request->name;
        $params['payTime'] = $request->time;
        $params['payAccount'] = $request->account;
        $params['payAmount'] = $request->amount;
        $params['state'] = '4';
        $result = $order->update($params);
        if (!$result) {
            return $this->makeJson(0, $result, 'REPORT_ERROR');
        }
        return $this->makeJson(1, null, null);
    }

    public function restockNotice(Request $request)
    {
        $params = $request->only('goodId');
        $params['userId'] = Auth::id();
        $check = RestockNotice::Where('goodId', $request->goodId)->Where('userId', Auth::id())->get();
        if (is_null($check)) {
            $result = RestockNotice::create($params);
            if ($result->id == '') {
                return $this->makeJson(0, $result, 'SET_RESTOCK_NOTICE_ERROR');
            }
        }
        return $this->makeJson(1, null, null);
    }

    public function getCategory(View $view)
    {
        $main = GoodCategory::Select('id', 'name')->Where('sub', '0')->Where('state', 1)->get();
        $categories = array();
        foreach ($main as $m) {
            $sub = GoodCategory::Select('id', 'name')->Where('sub', $m['id'])->Where('state', 1)->get();
            $index = $m['id'] . ',' . $m['name'];
            $categories[$index] = $sub;
        }
        $view->with('categories', $categories);

    }

    public function addFavorites(Request $request)
    {
        if (Auth::check()) {
            $goodId = $request->goodId;
            $userId = Auth::id();
            $params = [
                'goodId' => $goodId,
                'userId' => $userId,
            ];
            $check = GoodFavorite::Where('goodId', $goodId)->Where('userId', $userId)->get();
            if (count($check) == 0) {
                $result = GoodFavorite::create($params);
                if ($result->id == '') {
                    return $this->makeJson(0, $result, 'ADD_FAVORITES_ERROR');
                } else {
                    return $this->makeJson(1, null, null);
                }
            } else {
                return $this->makeJson(0, null, 'EXIST');
            }
        } else {
            return false;
        }
    }

    public function removeFavorites(Request $request)
    {
        if (Auth::check()) {
            $result = GoodFavorite::Where('userId', Auth::id())->Where('goodId', $request->goodId)->first()->delete();
            if (!$result) {
                return $this->makeJson(0, $result, 'REMOVE_FAVORITES_ERROR');
            } else {
                return $this->makeJson(1, null, null);
            }
        } else {
            return false;
        }
    }

    public function favoriteList()
    {
        $goodList = GoodFavorite::Where('userId', Auth::id())->get();
        return view('good.favoriteList', compact('goodList'));
    }

    public function tagList()
    {
        $tags = GoodTag::orderBy('created_at', 'DESC')->paginate(20);
        return view('good.tagList', compact('tags'));
    }

    public function tagCreate(Request $request)
    {
        $params = ['name' => $request->name];
        $result = GoodTag::create($params);
        if ($result->id == '') {
            return $this->makeJson(0, $result, 'TAG_CREATE_ERROR');
        }
        return $this->makeJson(1, null, null);
    }

    public function tagUpdate(GoodTag $tag, Request $request)
    {
        $result = $tag->update(['name' => $request->name]);
        if (!$result) {
            return $this->makeJson(0, $result, 'TAG_UPDATE_ERROR');
        }
        return $this->makeJson(1, null, null);
    }

    public function tagDelete(Request $request)
    {
        $list = $request->deleteList;
        if (count($list) > 0) {
            foreach ($list as $l) {
                $result = GoodTag::Where('id', $l)->first()->delete();
                if (!$result) {
                    return $this->makeJson(0, $result, 'TAG_DELETE_ERROR');
                }
                $tagForGood = Tag_for_good::Where('tagId', $l)->get();
                foreach ($tagForGood as $t) {
                    $result = $t->delete();
                    if (!$result) {
                        return $this->makeJson(0, $result, 'TAG_FOR_GOOD_DELETE_ERROR');
                    }
                }
            }
            return $this->makeJson(1, null, null);
        } else {
            return $this->makeJson(0, null, 'NO_TAG_POST');
        }

    }
}