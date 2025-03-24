<?php
namespace App\Http\Controllers\Web;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use DB;

use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductsController extends Controller {

	use ValidatesRequests;

	public function __construct()
    {
        $this->middleware('auth:web')->except('list');
    }

	public function list(Request $request) {

		$query = Product::select("products.*");

		$query->when($request->keywords, 
		fn($q)=> $q->where("name", "like", "%$request->keywords%"));

		$query->when($request->min_price, 
		fn($q)=> $q->where("price", ">=", $request->min_price));
		
		$query->when($request->max_price, fn($q)=> 
		$q->where("price", "<=", $request->max_price));
		
		$query->when($request->order_by, 
		fn($q)=> $q->orderBy($request->order_by, $request->order_direction??"ASC"));

		$products = $query->get();

		return view('products.list', compact('products'));
	}

	public function edit(Request $request, Product $product = null) {
		if(!auth()->user()) return redirect('/');
		
		$product = $product ?? new Product();
		
		if (!$product->exists) {
			$product->code = '';
			$product->name = '';
			$product->price = '';
			$product->model = '';
			$product->description = '';
			$product->photo = null;
		}
		
		return view('products.edit', compact('product'));
	}

	public function save(Request $request, Product $product = null)
{
    $this->validate($request, [
        'code' => 'required|unique:products,code,' . ($product->id ?? ''),
        'name' => 'required',
        'price' => 'required|numeric|min:0',
        'model' => 'required',
        'description' => 'required',
        'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
    ]);

    $data = $request->except('photo');
    
    if ($request->hasFile('photo')) {
        if ($product && $product->photo) {
            $oldPath = public_path('images/' . $product->photo);
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }
        }
        
        $file = $request->file('photo');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('images'), $filename);
        $data['photo'] = $filename;
    }

    if ($product && $product->exists) {
        $product->update($data);
    } else {
        $product = Product::create($data);
    }

    return redirect()->route('products_list');
	}

	public function delete(Request $request, Product $product) {

		if(!auth()->user()->hasPermissionTo('delete_products')) abort(401);

		$product->delete();

		return redirect()->route('products_list');
	}
}