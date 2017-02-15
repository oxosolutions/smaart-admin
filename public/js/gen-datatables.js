 $(function () {

    $('#apiusers').DataTable({
      processing: true,
      serverSide: true,
      ajax: route()+'/get_users',
      order: [[0,"desc"]],
      columns: [
                  { data: 'selector', name: 'selector',orderable: false, searchable: false},

            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'api_token', name: 'token' },
            { data: 'created_at', name: 'created_at' },
            { data: 'updated_at', name: 'updated_at' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false, "className": 'actions' },
      ]
    });


    $('#departments').DataTable({
      processing: true,
      serverSide: true,
      ajax: route()+'/list_depart',
      order:[[0,"desc"]],
      columns: [
            { data: 'id', name: 'id' },
            { data: 'dep_code', name: 'dep_code' },
            { data: 'dep_name', name: 'dep_name' },
            { data: 'created_by', name: 'created_by' },
            { data: 'created_at', name: 'created_at' },
            { data: 'updated_at', name: 'created_at' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false, "className": 'actions' },
      ]
    });

    $('#designations').DataTable({
      processing: true,
      serverSide: true,
      ajax: route()+'/list_desig',
      order:[[0,"desc"]],
      columns: [
            { data: 'id', name: 'id' },
            { data: 'designation', name: 'designation' },
            { data: 'created_at', name: 'created_at' },
            { data: 'updated_at', name: 'created_at' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false, "className": 'actions' },
      ]
    });

    $('#ministry').DataTable({
      processing: true,
      serverSide: true,
      ajax: route()+'/list_minist',
      order:[[0,"desc"]],
      columns: [
            { data: 'ministry_id', name: 'ministry_id' },
            { data: 'ministry_title', name: 'ministry_title' },
            { data: 'ministry_description', name: 'ministry_description' },
            { data: 'ministry_phone', name: 'ministry_phone' },
            { data: 'ministry_ministers', name: 'ministry_ministers' },
            { data: 'created_by', name: 'created_by' },
            { data: 'created_at', name: 'created_at' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false, "className": 'actions' },
      ]
    });

    $('#goals').DataTable({
      processing: true,
      serverSide: true,
      ajax: route()+'/list_goals',
      order: [[1,"asc"]],
      order:[[0,'desc']],
      columns: [
            { data: 'selector', name: 'selector',orderable: false, searchable: false},
            { data: 'goal_number', name: 'goal_number' },
            { data: 'goal_title', name: 'goal_title' },
            { data: 'goal_tagline', name: 'goal_tagline' },
            { data: 'goal_url', name: 'goal_url' },
            { data: 'goal_icon', name: 'goal_icon' },
            { data: 'created_by', name: 'created_by' },
            { data: 'created_at', name: 'created_at' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false, "className": 'actions' },
      ]
    });

    $('#schema').DataTable({
      processing: true,
      serverSide: true,
      ajax: route()+'/schema_list',
      order:[[0,'desc']],
      columns: [
            { data: 'id', name: 'id' },
            { data: 'schema_id', name: 'schema_id' },
            { data: 'schema_title', name: 'schema_title' },
            { data: 'schema_desc', name: 'schema_desc' },
            { data: 'created_by', name: 'created_by' },
            { data: 'created_at', name: 'created_at' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false, "className": 'actions' },
      ]
    });

    $('#Intervention').DataTable({
      processing: true,
      serverSide: true,
      ajax: route()+'/intervention_list',
      order:[[0,'desc']],
      columns: [
            { data: 'id', name: 'id' },
            { data: 'intervent_id', name: 'intervent_id' },
            { data: 'intervent_title', name: 'intervent_title' },
            { data: 'intervent_desc', name: 'intervent_desc' },
            { data: 'created_by', name: 'created_by' },
            { data: 'created_at', name: 'created_at' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false, "className": 'actions' },
      ]
    });

    $('#map').DataTable({
      processing: true,
      serverSide: true,
      ajax: route()+'/mapData',
      order:[[0,'desc']],
      columns: [
            { data: 'id', name: 'id' },
            { data: 'title', name: 'title' },
            { data: 'code', name: 'code' },
            { data: 'parent', name: 'parent' },
            { data: 'code_albha_2', name: 'code_albha_2' },
            { data: 'code_albha_3', name: 'code_albha_3' },
            { data: 'created_at', name: 'created_at' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false, "className": 'actions' },
      ]
    });

    $('#surrvey').DataTable({
      processing:true,
      serverSide:true,
      ajax: route()+'/surrveyData',
      order:[[0,'desc']],
      columns:[
       { data:'id',name:'id'},
       { data:'name',name:'name'},
       { data:'description',name:'description'},
       { data:'created_at',name:'created_at'},

       { data:'actions',name:'actions',orderable:false, searchable: false, "className": 'actions'},
      ]
    });

$('#fact').DataTable({
      processing: true,
      serverSide: true,
      ajax: route()+'/fact_data',
      order:[[0,'desc']],
      columns: [
            { data: 'id', name: 'id' },
            { data: 'fact_id', name: 'fact_id' },
            { data: 'fact_title', name: 'fact_title' },
            { data: 'fact_desc', name: 'fact_desc' },
            { data: 'created_by', name: 'created_by' },
            { data: 'created_at', name: 'created_at' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false, "className": 'actions' },
      ]
    });
    $('#target').DataTable({
      processing: true,
      serverSide: true,
      ajax: route()+'/target_list',
      order:[[0,'desc']],
      columns: [
            { data: 'id', name: 'id' },
            { data: 'target_id', name: 'target_id' },
            { data: 'target_title', name: 'target_title' },
            { data: 'target_desc', name: 'target_desc' },
            { data: 'created_by', name: 'created_by' },
            { data: 'created_at', name: 'created_at' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false, "className": 'actions' },
      ]
    });

    $('#resource').DataTable({
      processing: true,
      serverSide: true,
      ajax: route()+'/resource_list',
      order:[[0,'desc']],
      columns: [
            { data: 'id', name: 'id' },
            { data: 'resource_id', name: 'resource_id' },
            { data: 'resource_title', name: 'resource_title' },
            { data: 'resource_desc', name: 'resource_desc' },
            { data: 'created_by', name: 'created_by' },
            { data: 'created_at', name: 'created_at' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false, "className": 'actions' },
      ]
    });

    $('#indicator').DataTable({
      processing: true,
      serverSide: true,
      ajax: route()+'/indicators_list',
      order:[[0,'desc']],
      columns: [
            { data: 'id', name: 'id' },
            { data: 'indicator_title', name: 'indicator_title' },
            { data: 'targets_id', name: 'targets_id' },
            { data: 'created_by', name: 'created_by' },
            { data: 'created_at', name: 'created_at' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false, "className": 'actions' },
      ]
    });
    $('#pages').DataTable({
      processing: true,
      serverSide: true,
      ajax: route()+'/pages_list',
      order:[[0,"desc"]],
      columns: [
            { data: 'selector', name: 'selector',orderable: false, searchable: false},
            { data: 'id', name: 'id' },
            { data: 'page_title', name: 'page_title' },
            { data: 'page_subtitle', name: 'page_subtitle' },
            { data: 'page_slug', name: 'page_slug' },
            { data: 'status', name: 'status' },
            { data: 'created_by', name: 'created_by' },
            { data: 'created_at', name: 'created_at' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false, "className": 'actions' },
      ]
    });

    $('#visualisations').DataTable({
      processing: true,
      serverSide: true,
      ajax: route()+'/visualisation_list',
      order:[[0,'desc']],
      columns: [
            { data: 'id', name: 'id' },
            { data: 'dataset_id', name: 'dataset_id' },
            { data: 'visual_name', name: 'visual_name' },
            { data: 'settings', name: 'settings' },
            { data: 'options', name: 'options' },
            { data: 'created_by', name: 'created_by' },
            { data: 'created_at', name: 'created_at' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false, "className": 'actions' },
      ]
    });

    $('#visual').DataTable({
      processing: true,
      serverSide: true,
      ajax: route()+'/getVisual',
      order:[[0,'desc']],
      columns: [
            { data: 'id', name: 'id' },
            { data: 'visual_name', name: 'visual_name' },
            { data: 'dataset_id', name: 'dataset_id' },
            { data: 'columns', name: 'columns' },
            { data: 'created_by', name: 'created_by' },
            { data: 'created_at', name: 'created_at' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false, "className": 'actions' },
      ]
    });

    $('#visual_query').DataTable({
      processing: true,
      serverSide: true,
      ajax: route()+'/getQueries',
      order:[[0,'desc']],
      columns: [
            { data: 'id', name: 'id' },
            { data: 'visual_id', name: 'visual_id' },
            { data: 'query', name: 'query' },
            { data: 'created_by', name: 'created_by' },
            { data: 'created_at', name: 'created_at' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false, "className": 'actions' },
      ]
    });

    $('#datasets').DataTable({
      processing: true,
      serverSide: true,
      ajax: route()+'/dataset_list',
      order: [[0,"desc"]],
      columns: [
            { data: 'id', name: 'id' },
            { data: 'dataset_name', name: 'dataset_name' },
            { data: 'dataset_records', name: 'dataset_records' },
            { data: 'user_id', name: 'user_id' },
            { data: 'created_at', name: 'created_at' },
            { data: 'updated_at', name: 'updated_at' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false, "className": 'actions' },
      ]
    });

     $('#roles').DataTable({
      processing: true,
      serverSide: true,
      ajax: route()+'/list_roles',
      columns: [
            { data: 'name', name: 'name' },
            { data: 'description', name: 'description' },
            { data: 'created_at', name: 'created_at' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false, "className": 'actions' },
      ]
    });



     $('#permisson').DataTable({
      processing: true,
      serverSide: true,
      ajax: route()+'/list_permisson',
      columns: [
            { data: 'name', name: 'name' },
            { data: 'created_at', name: 'created_at' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false, "className": 'actions' },
      ]
    });

     $('#setting').DataTable({
      processing: true,
      serverSide: true,
      ajax: route()+'/list_setting',
      columns: [
            { data: 'name', name: 'name' },
            { data: 'created_at', name: 'created_at' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false, "className": 'actions' },
      ]
    });


    $('body').on('click','.delete', function(){

        if(confirm('Are you sure to delete ?')){

            window.location.href=$(this).attr('data-link');
        }else{

            return true;
        }
    });
  });

  $(document).ready(function() {

    $(".selectall").change(function(){
      if($(".selectall").prop("checked")){
        $(".item-selector").prop("checked", true);
      }else if($(".selectall").prop("checked",false)){
        $(".item-selector").prop('checked',false);
      }
     
    }); 
  });

  function delAll(url)
  {
    check =  confirm("Are You confirm to delete ?");
    if(check==true)
    {
      var inputs = $(".item-selector:checkbox:checked");
      var data = new Array();
      for(var i = 0; i < inputs.length; i++){
              data[i] =  $(inputs[i]).val();
          }
          $.ajax({
            url:route()+url,
            type:'GET',
            data:{'check':data},
            success:function(res)
            {
              window.location.reload();
            }
          });
      }
  }

  function delAllPages(url)
  {
    alert(url);
    var inputs = $(".item-selector:checkbox:checked");
    var data = new Array();
    for(var i = 0; i < inputs.length; i++){
            data[i] =  $(inputs[i]).val();
        }
        $.ajax({
          url:route()+url,
          type:'GET',
          data:{'check':data},
          success:function(res)
          {
           // window.location.reload();
          }


        });
  }