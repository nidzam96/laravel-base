<template>
	<div>

		<!-- START: FILTER -->
		<div class="card mb-4">
			<div class="card-header alert-info">
				Use the filter below to limit the log list. You are able to list log by <strong>Event</strong> and <strong>Role</strong>.
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-12 col-md-4">
						<div class="input-group">
			                <div class="input-group-prepend">
			                    <span class="input-group-text justify-content-center" style="width: 40px;">
			                        <i class="fas fa-calendar"></i>
			                    </span>
			                </div>
			                <input id="input-name" type="text" class="form-control" placeholder="Event" v-model="searchEvent">
			                <div class="input-group-append">
							    <button class="btn btn-outline-secondary" type="button" v-on:click="searchEvent = ''">
							    	<i class="fas fa-times"></i>
							    </button>
							</div>
			            </div>
					</div>
					<!-- <div class="col-12 col-md-4">
						<div class="input-group">
			                <div class="input-group-prepend">
			                    <span class="input-group-text justify-content-center" style="width: 40px;">
			                        <i class="fas fa-asterisk"></i>
			                    </span>
			                </div>
			                <input id="input-name" type="text" class="form-control" placeholder="Detail" v-model="searchDetail">
			                <div class="input-group-append">
							    <button class="btn btn-outline-secondary" type="button" v-on:click="searchDetail = ''">
							    	<i class="fas fa-times"></i>
							    </button>
							</div>
			            </div>
					</div> -->
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
	                                <th>Event</th>
	                                <th>Detail</th>
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
                data    : "event",
                defaultContent: '',
                "width": "10%",
            },
            {
                data    : "detail",
                defaultContent: '',
                "width": "35%",
            }];

export default {

    props: [
    	'api', 
        'apiRoles',
    	'canSearchRoles'
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
	        order: [[1, "desc"]],
	        pageLength: 5,
	        serverSide: true,
	        //ordering: false,
	        bSortCellsTop: true,
	        ajax: {
	            url     : vm.api,
	            type    : 'get',
	            "data": function (d) {
	            	d.latest = 1;
	            	d['role_by'] = vm.role_by;
                    d['event'] = vm.searchEvent;
                    d['detail'] = vm.searchDetail;
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
        	searchEvent 	: '',
        	searchDetail 	: '',
            role_by         : 0,
        	timeout 		: null
        }
    },

    watch: {
    	role_by: function(value) {
    		this.reload();
    	},
    	searchEvent: function(value) {
    		clearTimeout(this.timeout);
    		this.timeout = setTimeout(() => {
    			this.reload();
    		}, 1000);
    	},
    	searchDetail: function(value) {
    		clearTimeout(this.timeout);
    		this.timeout = setTimeout(() => {
    			this.reload();
    		}, 1000);
    	},
    },

    methods: {

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