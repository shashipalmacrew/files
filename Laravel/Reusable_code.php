<?php
//server side php script
 var table = $('#informationTable').DataTable({
            processing: true,
            serverSide: true,
			"scrollX": true,
			"scrollY": false,
			'lengthMenu': [ [100, 200, 300, 400, 500, 600, 700, 800 ,900 ,1000, -1], [100, 200, 300, 400, 500, 600, 700, 800 ,900 ,1000, 'All'] ],
					'pageLength': 100,
			ajax: "<?php echo url('/information')?>?apid="+pid,
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'datelastvoted',
                    name: 'datelastvoted'
                },
                {
                    data: 'cid',
                    name: 'cid'
                },
                {
                    data: 'pid',
                    name: 'pid'
                },
				{
                    data: 'regnum',
                    name: 'regnum'
                },
				{
                    data: 'name',
                    name: 'name'
                },
				{
                    data: 'addr',
                    name: 'addr'
                },
				{
                    data: 'gender',
                    name: 'gender'
                },
				{
                    data: 'race',
                    name: 'race'
                },
				{
                    data: 'twentytwentyage',
                    name: 'twentytwentyage'
                },
				{
                    data: 'abs_mov',
                    name: 'abs_mov'
                },
				{
                    data: 'abs_appdate',
                    name: 'abs_appdate'
                },
				{
                    data: 'appstatus',
                    name: 'appstatus'
                },
				{
                    data: 'abs_issuedate',
                    name: 'abs_issuedate'
                },
				{
                    data: 'abs_returndate',
                    name: 'abs_returndate'
                },
				{
                    data: 'ballotstatus',
                    name: 'ballotstatus'
                },
				{
                    data: 'statusreason',
                    name: 'statusreason'
                },
				{
                    data: 'absv',
                    name: 'absv'
                },
				{
                    data: 'provv',
                    name: 'provv'
                },
				{
                    data: 'suppv',
                    name: 'suppv'
                },
				{
                    data: 'concerns',
                    name: 'concerns'
                },
				{
                    data: 'completed',
                    name: 'completed'
                },
            ]
			
        });
		$("#PID").on("change", function() { //To change id as where clause in statement
         var pid= $(this).val();
         table.columns(3).search( pid ).draw(); 
       });
?>