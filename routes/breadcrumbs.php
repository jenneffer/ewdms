<?php

// Documents
Breadcrumbs::register('documents', function ($breadcrumbs) {
    $breadcrumbs->push('Documents', route('category'));
});

// Documents > Create
Breadcrumbs::register('createdocuments', function ($breadcrumbs) {
    $breadcrumbs->parent('documents');
    $breadcrumbs->push('Create Document', route('categories.create'));
});

// Documents > Category
Breadcrumbs::register('category', function ($breadcrumbs, $id) {
    $breadcrumbs->parent('documents');
    $category = App\Category::findName($id);
    $breadcrumbs->push( $category, route('documents.subcategory', $id));
});

// Documents > Category > Sub Category
Breadcrumbs::register('subcategory', function ($breadcrumbs, $parent_id, $id) {
    $breadcrumbs->parent('category', $parent_id);
    $subcat = App\Category::findName($id);
    $breadcrumbs->push($subcat, route('documents.subcategory.child', ['id'=>$id, 'parent_id'=>$parent_id]));
});

// Documents > Category > Sub Category > Item
Breadcrumbs::register('subcategoryitem', function ($breadcrumbs, $parent_id, $child_id, $id) {   
    $breadcrumbs->parent('subcategory',$parent_id,$child_id);    
    $subcatitem = App\Category::findName($id);
    $breadcrumbs->push($subcatitem, route('documents.category', $id));
});

// Documents > Category > Sub Category > Item > item1
Breadcrumbs::register('subcategoryitemlist', function ($breadcrumbs, $parent_id, $child_id, $id) {   
    $breadcrumbs->parent('subcategoryitem',$parent_id,$child_id, $id);        
    $breadcrumbs->push('All', route('documents.category', $id));
});

// Documents > Category > Sub Category > Item > item1-details
Breadcrumbs::register('subcategoryitemdetails', function ($breadcrumbs, $parent_id, $child_id,$category_id, $doc) {   
    $breadcrumbs->parent('subcategoryitemlist',$parent_id,$child_id,$category_id);        
    $breadcrumbs->push($doc->name, route('documents.show', $doc->id));
});

// Role
Breadcrumbs::register('roles', function ($breadcrumbs) {
    $breadcrumbs->push('Roles', route('roles.index'));
});

// Role > Add Role
Breadcrumbs::register('addroles', function ($breadcrumbs) {
    $breadcrumbs->parent('roles');   
    $breadcrumbs->push('Add Role', route('roles.create'));
});

// Role > Edit Role
Breadcrumbs::register('editroles', function ($breadcrumbs, $role) {
    $breadcrumbs->parent('roles');   
    $breadcrumbs->push('Edit Role', route('roles.edit', $role->id));
});

// Permission
Breadcrumbs::register('permission', function ($breadcrumbs) {
    $breadcrumbs->push('Permission', route('roles.index'));
});
// Role > Edit Role
Breadcrumbs::register('editpermission', function ($breadcrumbs, $permissions) {
    $breadcrumbs->parent('permission');   
    $breadcrumbs->push('Edit Permission', route('permissions.edit', $permissions->id));
});

// User
Breadcrumbs::register('users', function ($breadcrumbs) {
    $breadcrumbs->push('Users', route('users.index'));
});
// User > Edit User
Breadcrumbs::register('edituser', function ($breadcrumbs, $user) {
    $breadcrumbs->parent('users');   
    $breadcrumbs->push('Edit User', route('users.edit', $user->id));
});

// Category
Breadcrumbs::register('categories', function ($breadcrumbs) {
    $breadcrumbs->push('Categories', route('categories.index'));
});
// Category > Edit Category
Breadcrumbs::register('editcategories', function ($breadcrumbs, $cat) {
    $breadcrumbs->parent('categories');   
    $breadcrumbs->push('Edit Categories', route('categories.edit', $cat->id));
});