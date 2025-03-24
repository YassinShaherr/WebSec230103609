<?php
namespace App\Http\Controllers\Web;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Validation\Rules\Password;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;
use Artisan;



use App\Http\Controllers\Controller;
use App\Models\User;

class UsersController extends Controller {

	use ValidatesRequests;

    public function list(Request $request) {
        if(!auth()->user()->hasPermissionTo('show_users'))abort(401);
        $query = User::select('*');
        $query->when($request->keywords, 
        fn($q)=> $q->where("name", "like", "%$request->keywords%"));
        $users = $query->get();
        return view('users.list', compact('users'));
    }

	public function register(Request $request) {
        return view('users.register');
    }

    public function doRegister(Request $request) {
        $customerRole = Role::firstOrCreate(['name' => 'customer', 'guard_name' => 'web']);
        
        $viewProductsPermission = Permission::firstOrCreate(
            ['name' => 'view_products', 'guard_name' => 'web'],
            ['display_name' => 'View Products']
        );
        
        $addToCartPermission = Permission::firstOrCreate(
            ['name' => 'add_to_cart', 'guard_name' => 'web'],
            ['display_name' => 'Add to Cart']
        );
        
        $customerRole->givePermissionTo([$viewProductsPermission, $addToCartPermission]);

    	try {
    		$this->validate($request, [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|confirmed|min:8',
            ], [
                'password.confirmed' => 'The password confirmation does not match.',
                'password.min' => 'The password must be at least 8 characters.',
            ]);
    	}
    	catch(\Exception $e) {

    		return redirect()->back()->withInput($request->input())->withErrors('Invalid registration information.');
    	}

    	
    	$user =  new User();
	    $user->name = $request->name;
	    $user->email = $request->email;
	    $user->password = bcrypt($request->password);
	    $user->credit = 0.00;
	    $user->save();

        $user->assignRole('customer');

        return redirect('/');
    }

    public function login(Request $request) {
        return view('users.login');
    }

    public function doLogin(Request $request) {
    	
    	if(!Auth::attempt(['email' => $request->email, 'password' => $request->password]))
            return redirect()->back()->withInput($request->input())->withErrors('Invalid login information.');

        $user = User::where('email', $request->email)->first();
        Auth::setUser($user);

        return redirect('/');
    }

    public function doLogout(Request $request) {
    	
    	Auth::logout();

        return redirect('/');
    }

    public function profile(Request $request, User $user = null) {

        $user = $user??auth()->user();
        if(auth()->id()!=$user->id) {
            if(!auth()->user()->hasPermissionTo('show_users')) abort(401);
        }

        $permissions = [];
        foreach($user->permissions as $permission) {
            $permissions[] = $permission;
        }
        foreach($user->roles as $role) {
            foreach($role->permissions as $permission) {
                $permissions[] = $permission;
            }
        }

        return view('users.profile', compact('user', 'permissions'));
    }

    public function edit(Request $request, User $user = null) {
   
        $user = $user??auth()->user();
        if(auth()->id()!=$user?->id) {
            if(!auth()->user()->hasPermissionTo('edit_users')) abort(401);
        }
    
        $roles = [];
        foreach(Role::all() as $role) {
            $role->taken = ($user->hasRole($role->name));
            $roles[] = $role;
        }

        $permissions = [];
        $directPermissionsIds = $user->permissions()->pluck('id')->toArray();
        foreach(Permission::all() as $permission) {
            $permission->taken = in_array($permission->id, $directPermissionsIds);
            $permissions[] = $permission;
        }      

        return view('users.edit', compact('user', 'roles', 'permissions'));
    }

    public function save(Request $request, User $user) {

        if(auth()->id()!=$user->id) {
            if(!auth()->user()->hasPermissionTo('show_users')) abort(401);
        }

        $user->name = $request->name;
        $user->save();

        if(auth()->user()->hasPermissionTo('admin_users')) {

            $user->syncRoles($request->roles);
            $user->syncPermissions($request->permissions);

            Artisan::call('cache:clear');
        }

        //$user->syncRoles([1]);
        //Artisan::call('cache:clear');

        return redirect(route('profile', ['user'=>$user->id]));
    }

    public function delete(Request $request, User $user) {

        if(!auth()->user()->hasPermissionTo('delete_users')) abort(401);

        //$user->delete();

        return redirect()->route('users');
    }

    public function editPassword(Request $request, User $user = null) {

        $user = $user??auth()->user();
        if(auth()->id()!=$user?->id) {
            if(!auth()->user()->hasPermissionTo('edit_users')) abort(401);
        }

        return view('users.edit_password', compact('user'));
    }

    public function savePassword(Request $request, User $user) {

        if(auth()->id()==$user?->id) {
            
            $this->validate($request, [
                'password' => ['required', 'confirmed', Password::min(8)->numbers()->letters()->mixedCase()->symbols()],
            ]);

            if(!Auth::attempt(['email' => $user->email, 'password' => $request->old_password])) {
                
                Auth::logout();
                return redirect('/');
            }
        }
        else if(!auth()->user()->hasPermissionTo('edit_users')) {

            abort(401);
        }

        $user->password = bcrypt($request->password); //Secure
        $user->save();

        return redirect(route('profile', ['user'=>$user->id]));
    }

    public function addCredit(Request $request, User $user)
    {
        if (!auth()->user()->hasPermissionTo('admin_users')) {
            abort(401);
        }
        
        $this->validate($request, [
            'amount' => 'required|numeric|min:0.01',
        ]);
        
        $user->addCredit($request->amount);
        
        return redirect()->route('profile', ['user' => $user->id])
            ->with('success', 'Credit added successfully.');
    }

    public function editCredit(Request $request, User $user)
    {
        if (!auth()->user()->hasPermissionTo('admin_users')) {
            abort(401);
        }
        
        return view('users.edit_credit', compact('user'));
    }

    public function create(Request $request)
    {
        if (!auth()->user()->hasPermissionTo('admin_users')) {
            abort(401);
        }
        
        $roles = Role::all();
        $permissions = Permission::all();
        
        return view('users.create', compact('roles', 'permissions'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->hasPermissionTo('admin_users')) {
            abort(401);
        }
        
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:8',
            'credit' => 'nullable|numeric|min:0',
        ]);
        
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->credit = $request->credit ?? 0.00;
        $user->save();
        
        if ($request->roles) {
            $user->syncRoles($request->roles);
        }
        
        if ($request->permissions) {
            $user->syncPermissions($request->permissions);
        }
        
        Artisan::call('cache:clear');
        
        return redirect()->route('users')
            ->with('success', 'User created successfully');
    }

    /**
     * Ensure required permissions and roles exist
     */
    public function ensurePermissionsAndRoles()
    {
        // Create permissions if they don't exist
        $permissions = [
            ['name' => 'view_products', 'display_name' => 'View Products', 'guard_name' => 'web'],
            ['name' => 'add_to_cart', 'display_name' => 'Add to Cart', 'guard_name' => 'web']
        ];
        
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name'], 'guard_name' => $permission['guard_name']],
                ['display_name' => $permission['display_name']]
            );
        }
        
        // Create customer role if it doesn't exist
        $customerRole = Role::firstOrCreate(['name' => 'customer', 'guard_name' => 'web']);
        
        // Assign permissions to customer role
        $customerRole->givePermissionTo('view_products', 'add_to_cart');
        
        // Clear cache
        Artisan::call('cache:clear');
        
        return redirect()->back()->with('success', 'Permissions and roles updated successfully');
    }
} 