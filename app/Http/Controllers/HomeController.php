<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\SaleProducts;
use App\Models\Sales;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home', ['title' => 'Dashboard']);
    }

    /** Products */
    public function products()
    {
        $products = Products::all();
        return view('products', ['title' => 'Produtos', 'products' => $products]);
    }
    public function product($id)
    {
        $product = Products::find($id);
        if (!$product) {
            return view('errors.error', [
                'title' => 'Erro 404',
                'code' => '404',
                'message' => 'Registro não encontrado ou indisponível.'
            ]);
        }
        return view('product', ['title' => 'Detalhes do Produto', 'product' => $product]);
    }

    /** Sales */
    public function sales()
    {
        $sales = Sales::all();
        return view('sales', ['title' => 'Vendas', 'sales' => $sales]);
    }
    public function saleNew()
    {
        $products = Products::where('status', '1')->get();
        return view('saleNew', ['title' => 'Nova Venda', 'products' => $products]);
    }

    public function sale($id)
    {
        $sale = Sales::find($id);
        if (!$sale) {
            return view('errors.error', [
                'title' => 'Erro 404',
                'code' => '404',
                'message' => 'Registro não encontrado ou indisponível.'
            ]);
        }
        $products = Products::where('status', '1')->get();

        return view('sale', ['title' => 'Detalhes da Venda', 'sale' => $sale, 'products' => $products, "readonly" => ($sale->status == 1) ? "disabled" : ""]);
    }

    public function productsNew(Request $request)
    {
        $file = $request->file('file');
        $content = file($file->path());
        $cnt = 1;
        foreach ($content as $key => $product) {
            $product_attributes = explode("|", $product);
            if ($cnt > 1) {
                $attr = [
                    'reference' => utf8_encode($product_attributes[0]),
                    'description' => preg_replace("/\r|\n/", "", utf8_encode($product_attributes[1])),
                    'unity' => utf8_encode($product_attributes[2]),
                    'price' => floatval($product_attributes[3]),
                    'promotional_price' => (floatval($product_attributes[3]) - (floatval($product_attributes[3]) * 10 / 100)),
                    'ipi' => floatval($product_attributes[4]),
                    'status' => utf8_encode($product_attributes[5]),
                    'box_weight' => floatval($product_attributes[6]),
                    'box_price' => floatval($product_attributes[7]),
                    'category' => preg_replace("/\r|\n/", "", utf8_encode($product_attributes[8]))
                ];
                $prod[] = $attr;
            }
            $cnt++;
        }
        foreach ($prod as $product) {
            $ck = Products::where('reference', '=', $product['reference'])->first();
            if ($ck) {
                $ck->update($product);
                if (!$ck) {
                    return response()->json(['erro' => $ck->getErrors()]);
                }
            } else {
                $model = Products::create($product);
                if (!$model) {
                    return response()->json(['erro' => $model->getErrors()]);
                }
            }
        }
        return redirect()->route('products');
    }

    public function saleProdutct(Request $request)
    {
        if ($request->id) {
            $sale_products = SaleProducts::find($request->id);
            if ($sale_products) {
                return response()->json(["result" => $sale_products->toArray()]);
            }
            return response()->json(['erro' => 'Registro não encontrado.']);
        }
        return response()->json(['erro' => 'ID da linha não informada.']);
    }

    public function saleProductSave(Request $request)
    {
        if ($request->sale_id) {
            $sale =  Sales::find($request->sale_id);
        } else {
            $sale = new Sales;
            $sale->packed = ($request->packed == "true") ? true : false;
            $sale->detailed = ($request->detailed == "true") ? true : false;
            $sale->customer_name = ($request->customer_name) ? $request->customer_name : "";
            $sale->status = 0;
            $sale->total = 0;
            $sale->imported = 0;

            if (!$sale->save()) {
                return response()->json(['erro' => 'Não foi possivel inserir o item, tente novamente mais tarde.', 'details' => $sale->getErrors()]);
            }
        }

        $product = Products::find($request->product);
        if (!$product) {
            return response()->json(['erro' => 'Produto não encontrado']);
        }
        $product_id = $product->id;

        if (!$request->quantity) {
            return response()->json(['erro' => 'Quantidade não não informada']);
        }
        $quantity = floatval($request->quantity);

        $price = $product->price;
        $total = ($quantity * $price);

        if ($request->sale_product_id) {
            $sale_products = SaleProducts::find($request->sale_product_id);
            $sale_products->product_id = $product_id;
            $sale_products->quantity = $quantity;
            $sale_products->price = $price;
            $sale_products->total = $total;
        } else {
            $sale_products = new SaleProducts;
            $sale_products->sale_id =  $sale->id;
            $sale_products->product_id = $product_id;
            $sale_products->quantity = $quantity;
            $sale_products->price = $price;
            $sale_products->total = $total;
        }

        if ($sale_products->save()) {
            $sale->total = ($sale->total + $sale_products->total);
            if (!$sale->save()) {
                return response()->json(['erro' => 'Não foi possivel inserir o item, tente novamente mais tarde.', 'details' => $sale->getErrors()]);
            }
            return response()->json(array_merge($sale_products->toArray(), ["sale" => $sale->toArray()]));
        }
        return response()->json(['erro' => 'Não foi possivel inserir o item, tente novamente mais tarde.', 'details' => $sale_products->getErrors()]);
    }

    public function saleSave(Request $request)
    {
        if ($request->id) {
            $sale =  Sales::find($request->id);
        } else {
            $sale = new Sales;
        }
        $total = 0;

        $sale_products = $sale->saleProducts()->get();

        foreach ($sale_products as $sale_product) {
            $product = $sale_product->product()->first();

            if ($request->detailed && $request->detailed == "true") {
                $total += ($sale_product->quantity * $product->price);
            } else {
                $total += ($sale_product->quantity * $product->promotional_price);
            }
        }
        $sale->status = ($request->status && $request->status == 1) ? true : false;;
        $sale->total =  $total;
        $sale->packed = ($request->packed && $request->packed == "true") ? true : false;
        $sale->detailed = ($request->detailed && $request->detailed == "true") ? true : false;
        $sale->customer_name = ($request->customer_name && $request->customer_name != "" && $request->detailed && $request->detailed == "true") ? $request->customer_name : "";

        if (!$sale->save()) {
            return response()->json(['erro' => 'Não foi possivel inserir o item, tente novamente mais tarde.', 'details' => $sale->getErrors()]);
        }

        return response()->json($sale->toArray());
    }

    public function saleProductGetPrice(Request $request)
    {
        if ($request->id) {
            $id = $request->id;
            $product = Products::find($id);
            if ($product) {

                if (substr($product->reference, 0, 3) == "TBC" || substr($product->reference, 0, 3) == "DBC") {

                    $box = true;
                    $box_weight = $product->box_weight;
                    $box_price = $product->box_price;
                } else {

                    $box = false;
                    $box_weight = 0;
                    $box_price = 0;
                }

                if (!$request->detailed) {
                    return response()->json(['price' => $product->price, 'box' => $box, "box_weight" => $box_weight, "box_price" => $box_price]);
                }
                return response()->json(['price' => $product->promotional_price, 'box' => $box, "box_weight" => $box_weight, "box_price" => $box_price]);
            }
            return response()->json($product->getErrors());
        }
        return response()->json(['erro' => 'Registro não encontrado']);
    }

    public function saleList(Request $request)
    {
        if ($request->id) {
            $id = $request->id;
            $sale_products = SaleProducts::with('product')->where("sale_id", "=", $id)->get();
            if ($sale_products) {
                return response()->json(["items" => $sale_products->toArray()]);
            }
            return response()->json($sale_products->getErrors());
        }
        return response()->json(['erro' => 'Registro não encontrado']);
    }
    public function saleProductDelete(Request $request)
    {
        if ($request->id) {
            $sale_products = SaleProducts::find($request->id);
            if ($sale_products) {
                if ($sale_products->delete()) {
                    return response()->json(["result" => "registro excluído com sucesso."]);
                }
                return response()->json(['erro' => $sale_products->getErrors()]);
            }
            return response()->json(['erro' => 'Registro não encontrado.']);
        }
        return response()->json(['erro' => 'ID da linha não informada.']);
    }

    public function saleDelete(Request $request)
    {
        if ($request->id) {
            $sale = Sales::find($request->id);
            if ($sale) {
                if ($sale->delete()) {
                    return response()->json(["result" => "registro excluído com sucesso."]);
                }
                return response()->json(['erro' => $sale->getErrors()]);
            }
            return response()->json(['erro' => 'Registro não encontrado.']);
        }
        return response()->json(['erro' => 'ID da linha não informada.']);
    }
}
