<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Good;
use App\Models\GoodCategory;
use App\Models\GoodDetail;
use App\Models\GoodOrder;
use App\Models\GoodOrderPayment;
use App\Models\GoodOrderState;
use App\Models\GoodStock;
use App\Models\GoodType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
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
        if (is_null($good)) {
            return view('good.goodEditor', compact('categories'));
        } else {
            $good = Good::Where('serial', $good)->first();
            return view('good.goodEditor', compact('categories', 'good'));
        }
    }

    public function goodCreate(Good $good = null, Request $request)
    {
        try {
            // return $this->makeJson(0, gettype($good->gallery), 'FIRE');
            $update = false;
            $params = $request->only('name', 'caption', 'category', 'hot');

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
                    $result = Storage::delete(str_replace('storage', 'public', $good->cover));
                    if (!$result) {
                        return $this->makeJson(0, $result, 'OLD_COVER_DELETE_ERROR');
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
            $deleteGallery = $request->deleteGallery; //獲得前台傳來的刪除圖片陣列
            if ($update) {
                $gallery = $good->gallery; //提取舊資料中的圖片陣列
            } else {
                $gallery = array(); //建立空的圖片陣列
            }
            if (!is_null($deleteGallery) && $deleteGallery != "") {
                //假刪除圖片的陣列不為null
                $deleteGallery = explode(',', $request->deleteGallery); //將資料轉為陣列
                $temp = array();
                $old = $good->gallery; //獲得舊資料
                for ($i = 0; $i < count($old); $i++) {
                    $needSave = true;
                    for ($j = 0; $j < count($deleteGallery); $j++) {
                        if ($old[$i] == $deleteGallery[$j]) { //若是現有的圖片與刪除對象名稱相同時
                            $de = str_replace('storage', 'public', $deleteGallery[$j]); //更改為實際儲存路徑
                            if (Storage::exists($de)) { //若該檔案存在時刪除該檔案
                                $result = Storage::delete($de);
                                if (!$result) {
                                    return $this->makeJson(0, $result, 'DELETE_OLD_IMAGE_ERROR');
                                }
                            }
                            $needSave = false;
                            break; //跳離目前的迴圈
                        }
                    }
                    if ($needSave) {
                        array_push($temp, $old[$i]);
                    }

                }
                $gallery = $temp;
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

            // return $this->makeJson(0, ['old' => $old, 'temp' => $temp, 'gallery' => $gallery, 'delete' => $deleteGallery, 'galleries' => $request->galleries], 'HERE');

            // return $this->makeJson(0, $gallery, null);
            $gallery = base64_encode(serialize($gallery));

            $result = $good->update(['gallery' => $gallery]);
            if (!$result) {
                return $this->makeJson(0, $result, 'GALLERY_INSERT_ERROR');
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
        $id = Good::Where('serial', $id)->first();
        $result = $id->delete();
        if (!$result) {
            return $this->makeJson(0, $result, 'GOOD_DELETE_ERROR');
        } else {
            return $this->makeJson(1, null, null);
        }
    }

    public function goodList($category = null)
    {
        $goods = Good::Where('state', 1)->orWhere('state', 0);
        if (!is_null($category)) {
            $goods = $goods->Where('category', $category);
        }
        $goods = $goods->paginate(12);
        return view('good.editList', compact('goods', 'category'));
    }

    public function showGoodList($category = null)
    {
        $goods = Good::Where('state', 1);
        if (!is_null($category)) {
            $goods = $goods->Where('category', $category);
        }
        $goods = $goods->paginate(12);
        return view('good.list', compact('goods', 'category'));
    }

    public function showGood($serial)
    {
        $good = Good::Where('serial', $serial)->first();
        if (is_null($good)) {
            return view('good.unknown');
        } else {
            return view('good.show', compact('good'));
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
        foreach ($types as $t) {
            $params = array();
            if ($t[2] != 0) {
                $params['goodId'] = $t[0];
                $params['goodType'] = $t[1];
                if ($t[3] == 'true') {
                    $params['import'] = $t[2];
                } else {
                    $params['export'] = $t[2];
                }
                $result = GoodStock::create($params);
                if ($result->id == '') {
                    return $this->makeJson(0, $result, 'STOCK_CREATE_ERROR');
                }
            }
        }
        return $this->makeJson(1, null, null);
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
        return view('good.listBackend', compact('orders', 'status', 'state', 'start', 'end', 'page'));
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

        return view('good.showBackend', compact('order'));
    }

    public function showOrder($serial)
    {
        $order = $this->getOrder('user', $serial);
        return view('good.orderShow', compact('order'));

    }

    private function getOrder($type, $serial)
    {
        $order = GoodOrder::where('serial', $serial);
        if ($type == 'user') {
            $user = Auth::id();
            $order = $order->where('userId', $user);
        }
        $order = $order->first();
        // dd($order->serial);
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
        $params = $request->only('name', 'address', 'freight', 'total', 'pay', 'state');
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
        $user = Auth::id();
        if (Cache::has(Auth::id())) {
            $orderParams = Cache::get(Auth::id());
        } else {
            $orderParams = array();
        }
        foreach ($orders as $order) {
            $number = $order[1];
            if ($number > 0) {
                $type = $order[0];
                $name = (Good::Where('id', $id)->first())['name'];
                $data = GoodType::Select('name', 'price')->Where('goodId', $id)->Where('type', $type)->first();
                $typeName = $data['name'];
                $price = intval($data['price']);
                array_push($orderParams, [$id, $type, $name, $typeName, $number, $price]);
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
            $id = Auth::id();
            $list = Cache::get($id);
            $action = null;
            $index = $request->index;
            unset($list[$index]);
            if (count($list) == 0) {
                $result = Cache::forget(Auth::id());
                $action = 'reload';
            } else {
                $result = Cache::put(Auth::id(), $list);
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

    public function orderCreate($serial = null, Request $request)
    {
        if (!$request->has('name') || $request->name == '') {
            return $this->makeJson(0, null, 'NO_NAME');
        }

        if (!$request->has('tel') || $request->tel == '') {
            return $this->makeJson(0, null, 'NO_TEL');
        }

        if ($request->pay != '2') {
            if (!$request->has('zipcode') || $request->zipcode == '') {
                return $this->makeJson(0, null, 'NO_ZIPCODE');
            }

            if (!$request->has('city') || $request->city == '') {
                return $this->makeJson(0, null, 'NO_CITY');
            }

            if (!$request->has('dist') || $request->dist == '') {
                return $this->makeJson(0, null, 'NO_DISTRICT');
            }

            if (!$request->has('address') || $request->address == '') {
                return $this->makeJson(0, null, 'NO_ADDRESS');
            }
        }

        $orderParams = $request->only('name', 'tel', 'zipcode', 'pay', 'freight', 'remark');
        $orderParams['userId'] = Auth::id();
        $total = 0;
        $orderParams['total'] = $total;
        $orderParams['state'] = 1;
        if ($request->pay != '2') {
            $city = $request->city;
            $dist = $request->dist;
            $address = $request->address;
            $address = $city . $dist . $address;
            $orderParams['address'] = $address;
        } else {
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
        $details = Cache::get(Auth::id());
        foreach ($details as $d) {
            $temp = array();
            $temp['orderId'] = $id;
            $temp['goodId'] = $d[0];
            $temp['type'] = $d[1];
            $temp['quantity'] = $d[4];
            $temp['amount'] = $d[5];
            $total += intval($d[4]) * intval($d[5]);
            $result = GoodDetail::create($temp);
            if ($result->id == '') {
                return $this->makeJson(0, $result, 'DETAIL_CREATE_ERROR');
            }
        }
        $result = $good->update(['total' => $total]);
        if (!$result) {
            return $this->makeJson(0, $result, 'TOTAL_INSERT_ERROR');
        }
        Cache::forget(Auth::id());
        return $this->makeJson(1, $serial, null);
    }

    public function orderComplete($serial)
    {
        $order = GoodOrder::Where('serial', $serial)->first();
        return view('good.complete', compact('order'));
    }

    public function changeOrder(GoodOrder $order, Request $request)
    {
        $params = $request->only('name', 'tel', 'pay', 'freight', 'remark');
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
}