<template>
	<div>

		<!-- START: FILTER -->
		<div class="card mb-4">
			<div class="card-header alert-info">
				Use the filter below to limit the user list. You are able to list user by <strong>Name</strong>, <strong>E-mail</strong> and <strong>Role</strong>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-12 col-md-4">
						<div class="input-group">
			                <div class="input-group-prepend">
			                    <span class="input-group-text justify-content-center" style="width: 40px;">
			                        <i class="fas fa-user-alt"></i>
			                    </span>
			                </div>
			                <input id="input-name" type="text" class="form-control" placeholder="Name" v-model="searchName">
			                <div class="input-group-append">
							    <button class="btn btn-outline-secondary" type="button" v-on:click="searchName = ''">
							    	<i class="fas fa-times"></i>
							    </button>
							</div>
			            </div>
					</div>
					<div class="col-12 col-md-4">
						<div class="input-group">
			                <div class="input-group-prepend">
			                    <span class="input-group-text justify-content-center" style="width: 40px;">
			                        <i class="far fa-envelope"></i>
			                    </span>
			                </div>
			                <input id="input-email" type="text" class="form-control" placeholder="Email" v-model="searchEmail">
			                <div class="input-group-append">
							    <button class="btn btn-outline-secondary" type="button" v-on:click="searchEmail = ''">
							    	<i class="fas fa-times"></i>
							    </button>
							</div>
			            </div>
					</div>
					<div v-if="canSearchRoles" class="col-12 col-md-2 custom-select2">
						<select2 
		                	id="role_by" 
		                	name="role_by" 
		                	class="form-control" 
		                	v-model="role_by"
		                	:options="roles"
		                >
		                </select2>
					</div>
					<div class="col-12 col-md-2">
						<button class="btn btn-primary btn-block btn-md" v-on:click="create" :disabled="!canCreateUser">Create User</button>
					</div>
				</div>
			</div>
		</div>
		<!-- END: FILTER -->
		
		<div class="row row-table">
	        <div class="col-12">
	            <div class="card card-table shadow-sm">
	                <div class="table-responsive mb-0">
	                    <table id="table-users" class="table responsive no-wrap">
	                        <thead>
	                            <tr>
	                                <th class="text-center">ID</th>
	                                <th>Name</th>
	                                <th>Email</th>
	                                <th>Role</th>
	                                <th class="text-center">Action</th>
	                            </tr>
	                        </thead>
	                        <tbody></tbody>
	                    </table>
	                </div>
	            </div>
	        </div>
	    </div>
	</div>
</template>

<script>

var columns = [{
                data: "id", 
                defaultContent: '',
                "width": "5%",
                visible : true,
            },
            {
                data    : "name",
                defaultContent: '',
                "width": "35%",
            },
            {
                data    : "email",
                defaultContent: '',
                "width": "30%"
            },
            {
                data    : "role",
                defaultContent: '',
                orderable       : false,
                "width": "15%"
            },
            {
                data            : "action", 
                defaultContent  : '', 
                orderable       : false,
                searchable      : false, 
                visible 		: true,
                "width": "15%",

                // The `data` parameter refers to the data for the cell (defined by the
                // `data` option, which defaults to the column being worked with, in
                // this case `data: 0`.
                "render": function (data, type, row) {
                    //var html = data['view'] + '|' + data['edit'] + '|' + data['print'];
                    var html = '<div class="btn-group w-100">';
                        html += '<button data-action="update" class="btn btn-outline-secondary btn-sm btn-update-user" ' + (!data['update'] ? 'disabled' : '') + '>Update</button>';
                        html += '<button data-action="delete" class="btn btn-outline-danger btn-sm btn-delete-user" ' + (!data['delete'] ? 'disabled' : '') + '>Delete</button>';
                    html += '</div>';
                    return html;
                }
            }];

export default {

    props: [
    	'api', 
        'apiRoles',
    	'canSearchRoles',
    	'canCreateUser',
    	'limit'
    ],

    mounted() {

    	var vm = this;
  		var table = $(this.$el).find('table').DataTable({
	        /*language: {
	            "emptyTable"    : "Tiada rekod",
	            "paginate"      : {
	                "previous"  : "Sebelum",
	                "next"      : "Seterusnya"
	            }
	        },*/
	        dom: '<"row"<"col-12"t>>r<"card-footer py-2 px-3"p>',
	        order: [[0, "desc"]],
	        pageLength: vm.limit,
	        serverSide: true,
	        //ordering: false,
	        bSortCellsTop: true,
	        ajax: {
	            url     : vm.api,
	            type    : 'get',
	            "data": function (d) {
	            	d.latest = 1;
	            	d['role_by'] = vm.role_by;
	            	d['name'] = vm.searchName;
	            	d['email'] = vm.searchEmail;
	            	// Here is to post custom data
	                //d['api_token'] 	= vm.token;
	                // d.custom = $('#myInput').val();
	                // etc
	            }
	        },
	        processing: true,
	        rowId: 'id',
	        columns: columns,
	        autoWidth: false,
	        "columnDefs": [
	            // { className: "text-right", "targets" : [3, 4, 5]},
	            { className: "text-center", "targets": [0]}
	        ],
	        "drawCallback": function(settings) {
	            $('#table-users tbody > tr').on('click', 'button', function () {
                    var btnAction = $(this).data('action');
                    var tr = $(this).parent().parent().parent();
			        var data = table.row(tr).data();
			        // alert( 'You clicked on '+data[0]+'\'s row' );
			        // console.log(data);
			        vm.onBtnAction(data, btnAction);
			    });
	        }
	    });

  		if (this.canSearchRoles) {
	  		$.get(this.apiRoles)
	  		.done(response => {
	  			this.roles = this.roles.concat(response);
	  		});
  		}
	},

    data() {
        return {
        	roles 			: [{'id': 0, 'text': 'All Roles'}],
        	searchName 	    : '',
            searchEmail     : '',
            role_by            : 0,
        	timeout 		: null
        }
    },

    watch: {
    	role_by: function(value) {
    		this.reload();
    	},
    	searchName: function(value) {
    		clearTimeout(this.timeout);
    		this.timeout = setTimeout(() => {
    			this.reload();
    		}, 1000);
    	},
    	searchEmail: function(value) {
    		clearTimeout(this.timeout);
    		this.timeout = setTimeout(() => {
    			this.reload();
    		}, 1000);
    	}
    },

    methods: {

        create() {
            this.$emit('user-action-create');
        },

        onBtnAction(data, action) {
            this.$emit('user-action-' + action, data);
        },

        setIsLoading(isLoading) {
        	this.isLoading = isLoading;
        },

        reload() {
        	var table = $(this.$el).find('table').DataTable();
        	table.ajax.reload();
        }
    }
}
</script>