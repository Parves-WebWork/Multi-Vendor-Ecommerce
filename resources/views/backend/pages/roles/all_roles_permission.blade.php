@extends('admin.admin_dashboard')
@section('admin')

<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <!-- ... (breadcrumb code remains the same) ... -->
    </div>
    <!--end breadcrumb-->

    <hr/>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="example" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Sl</th>
                            <th>Roles Name </th> 
                            <th>Permission </th>
                            <th>Action</th> 
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($roles as $key => $item)
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td>{{ $item->name }}</td>
                            <td>
                                @foreach($item->permissions as $perm)
                                <span class="badge rounded-pill bg-danger">{{ $perm->name }}</span>
                                @endforeach
                            </td>
                            <td>
                                <a href="{{ route('admin.edit.roles',$item->id) }}" class="btn btn-info">Edit</a>
                                <a href="{{ route('admin.delete.roles',$item->id) }}" class="btn btn-danger" id="delete">Delete</a>
                            </td> 
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Sl</th>
                            <th>Roles Name </th> 
                            <th>Permission </th>
                            <th>Action</th> 
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
