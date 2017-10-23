<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\User;
use App\Organization;
use App\Log;
use App\Locked_Data;
use App\SocialAccountService;

use App\Role;
use App\Permission;

use App\Events\Event;
use App\Events\EventChrome;

use Config;
use Illuminate\Support\Facades\Session;

use Symfony\Component\Console\Output\ConsoleOutput;

class RolePermissionController extends Controller {

	public function view(Request $request) {
		$roles = Role::with('perms')->get();

    	return view('role.view',compact('roles'));
	}

	public function add(Request $request) {
		$display = "add";

		$permissions = Permission::all();

		return view('role.create',compact('display', 'permissions'));
	}

	public function edit(Request $request, $role_id) {
		$display = "edit";

		$role = Role::where('id',$role_id)->first();
		$permissions = Permission::all();
		
		return view('role.create',compact('display','role', 'permissions'));
	}

	public function create(Request $request) {
		$output = new ConsoleOutput();

		$output->writeln("Calling create");

		$this->validate($request, [
            'status' => 'required',
            'rolename' => 'required',
            'roledisplayname' => 'required ',
            'roledescription' => 'required',
            'newpermission' => 'required'
        ]);

		$status = "fail";

		if($request->newpermission == "-1") {
			$newPermission = new Permission();
			$newPermission->name = $request->permissionname;
			$newPermission->display_name = $request->permissiondisplayname;
			$newPermission->description = $request->permissiondescription;
			$newPermission->save();

			$permission_name = $request->permissionname;
		} else {
			$output->writeln($request->newpermission);
			$permission_name = $request->newpermission;
		}

		if($request->status == "add") {
			$newRole = new Role();
			$newRole->name = $request->rolename;
			$newRole->display_name = $request->roledisplayname; // optional
			$newRole->description = $request->roledescription; // optional
			$newRole->save();

			$status = "success";
		} elseif ($request->status == "edit") {
			$output->writeln("Edit");
			$newRole = Role::find($request->roleid);
			$newRole->display_name = $request->roledisplayname; // optional
			$newRole->description = $request->roledescription; // optional
			$newRole->update();

			$status = "success";
		}

		if($status == "success") {
			$output->writeln("status success");
			$assign_permission = Permission::where('name',$permission_name)->first();
			$newRole->attachPermission($assign_permission); // Adds this permission along with earlier 1 
		}

		return redirect()->back()->with(['status' => $status, 'display' => $request->status]);
	}

    public function role(Request $request) {
		try {
			$owner = new Role();
			$owner->name = 'owner';
			$owner->display_name = 'Company Owner'; // optional
			$owner->description = 'User is the owner of 1 or more companies + has admin\'s features'; // optional
			$owner->save();

			$admin = new Role();
			$admin->name = 'admin';
			$admin->display_name = 'User Administrator'; // optional
			$admin->description = 'User is allowed to manage and edit other users'; // optional
			$admin->save();

			$member = new Role();
			$member->name = 'member';
			$member->display_name = 'Employee of a company'; // optional
			$member->description = 'User is allowed to manage and edit his/her details'; // optional
			$member->save();


			$addCompanies = new Permission();
			$addCompanies->name = 'add-companies';
			$addCompanies->display_name = 'Add new companies'; // optional
			// Allow a user to...
			$addCompanies->description = 'Add new companies under that organization.'; // optional
			$addCompanies->save();

			$editUser = new Permission();
			$editUser->name = 'edit-users';
			$editUser->display_name = 'Edit Users'; // optional
			// Allow a user to...
			$editUser->description = 'Edit existing users or add new Users.'; // optional
			$editUser->save();

			$editPersonal = new Permission();
			$editPersonal->name = 'edit-personal';
			$editPersonal->display_name = 'Edit Personal User details'; // optional
			// Allow a user to...
			$editPersonal->description = 'Edit Personal user details, logs & project work.'; // optional
			$editPersonal->save();


			$owner->attachPermissions(array($addCompanies, $editUser, $editPersonal));// equivalent to $admin->perms()->sync(array($addCompanies->id));
			$admin->attachPermissions(array($editUser, $editPersonal));
			$member->attachPermission($editPersonal);

			return  response()->json(['status' => 'Successfully created all the roles']);	
		} catch (Exception $e) {
			return  response()->json(['status' => 'Failed - Didn\'t create roles']);
		}		
	}
}