<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<script type="text/javascript">
    $.extend($.fn.datebox.defaults,{
        formatter:function(date){
            var y = date.getFullYear();
            var m = date.getMonth()+1;
            var d = date.getDate();
            return y+'-'+(m<10?('0'+m):m)+'-'+(d<10?('0'+d):d);
        },
        parser:function(s){
            if (!s) return new Date();
            var ss = (s.split('-'));
            var y = parseInt(ss[0],10);
            var m = parseInt(ss[1],10);
            var d = parseInt(ss[2],10);
            if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
                return new Date(y,m-1,d);
            } else {
                return new Date();
            }
        }
    });
</script>
<!-- Data Grid -->
<table id="grid-master_employee"
    data-options="pageSize:50, multiSort:true, remoteSort:true, rownumbers:true, singleSelect:true, 
                fit:true, fitColumns:true, toolbar:toolbar_master_employee">
    <thead>
        <tr>
            <th data-options="field:'ck',checkbox:true" ></th>
            <th data-options="field:'m_employee_id'"        width="5%" align="center" sortable="true">ID</th>
            <th data-options="field:'m_employee_name'"      width="15%" halign="center" align="left"   sortable="true">Name</th>
            <th data-options="field:'m_employee_birth'"     width="7.5%" halign="center" align="center" sortable="true">Birth</th>
            <th data-options="field:'m_employee_addr'"      width="25%" halign="center" align="left"   sortable="true">Address</th>
            <th data-options="field:'m_dept_name'"          width="10%" halign="center" align="center" sortable="true">Department</th>
            <th data-options="field:'m_employee_hired'"     width="7.5%" halign="center" align="center" sortable="true">Hired</th>
            <th data-options="field:'m_employee_termit'"    width="7.5%" halign="center" align="center" sortable="true">Termination</th>
            <th data-options="field:'m_rank_name'"          width="7.5%" halign="center" align="center" sortable="true">Rank</th>
            <th data-options="field:'m_status_name'"        width="7.5%" halign="center" align="center" sortable="true">Status</th>
            <th data-options="field:'m_shuttle_name'"       width="7.5%" halign="center" align="center" sortable="true">Shuttle</th>
        </tr>
    </thead>
</table>

<script type="text/javascript">
    var toolbar_master_employee = [{
        id      : 'master_employee-new',
        text    : 'New',
        iconCls : 'icon-new_file',
        handler : function(){masterEmployeeCreate();}
    },{
        id      : 'master_employee-edit',
        text    : 'Edit',
        iconCls : 'icon-edit',
        handler : function(){masterEmployeeUpdate();}
    },{
        id      : 'master_employee-delete',
        text    : 'Delete',
        iconCls : 'icon-cancel',
        handler : function(){masterEmployeeHapus();}
    },{
        text    : 'Refresh',
        iconCls : 'icon-reload',
        handler : function(){masterEmployeeRefresh();}
    }];
    
    $('#grid-master_employee').datagrid({
        onLoadSuccess   : function(){
            $('#master_employee-edit').linkbutton('disable');
            $('#master_employee-delete').linkbutton('disable');
        },
        onSelect        : function(){
            $('#master_employee-edit').linkbutton('enable');
            $('#master_employee-delete').linkbutton('enable');
        },
        onClickRow      : function(){
            $('#master_employee-edit').linkbutton('enable');
            $('#master_employee-delete').linkbutton('enable');
        },
        onDblClickRow   : function(){
            masterEmployeeUpdate();
        },
        view            :scrollview,
        remoteFilter    :true,
        url             :'<?php echo site_url('master/employee/index'); ?>?grid=true'})
    .datagrid('enableFilter');

    function masterEmployeeRefresh() {
        $('#master_employee-edit').linkbutton('disable');
        $('#master_employee-delete').linkbutton('disable');
        $('#grid-master_employee').datagrid('reload');
    }
    
    function masterEmployeeCreate() {
        $('#dlg-master_employee').dialog({modal: true}).dialog('open').dialog('setTitle','Add Data');
        $('#fm-master_employee').form('clear');
        url = '<?php echo site_url('master/employee/create'); ?>';
    }
    
    function masterEmployeeUpdate() {
        var row = $('#grid-master_employee').datagrid('getSelected');
        if(row){
            $('#dlg-master_employee').dialog({modal: true}).dialog('open').dialog('setTitle','Edit Data');
            $('#fm-master_employee').form('load',row);
            url = '<?php echo site_url('master/employee/update'); ?>/' + row.m_employee_id;
        }
        else{
             $.messager.alert('Info','Data not selected !','info');
        }
    }
    
    function masterEmployeeSave(){
        $('#fm-master_employee').form('submit',{
            url: url,
            onSubmit: function(){
                return $(this).form('validate');
            },
            success: function(result){
                var result = eval('('+result+')');
                if(result.success){
                    $('#dlg-master_employee').dialog('close');
                    masterEmployeeRefresh();
                    $.messager.show({
                        title   : 'Info',
                        msg     : '<div class="messager-icon messager-info"></div><div>Save Success</div>'
                    });
                }
                else{
                    var win = $.messager.show({
                        title   : 'Error',
                        msg     : '<div class="messager-icon messager-error"></div><div>Save Failed !</div>'+result.error
                    });
                    win.window('window').addClass('bg-error');
                }
            }
        });
    }
        
    function masterEmployeeHapus(){
        var row = $('#grid-master_employee').datagrid('getSelected');
        if (row){
            var win = $.messager.confirm('Confirm','Delete Employee. '+row.m_employee_name+' ?',function(r){
                if (r){
                    $.post('<?php echo site_url('master/employee/delete'); ?>',{m_employee_id:row.m_employee_id},function(result){
                        if (result.success){
                            masterEmployeeRefresh();
                            $.messager.show({
                                title   : 'Info',
                                msg     : '<div class="messager-icon messager-info"></div><div>Delete Success</div>'
                            });
                        }
                        else{
                            $.messager.show({
                                title   : 'Error',
                                msg     : '<div class="messager-icon messager-error"></div><div>Delete Failed !</div>'+result.error
                            });
                        }
                    },'json');
                }
            });
            win.find('.messager-icon').removeClass('messager-question').addClass('messager-warning');
            win.window('window').addClass('bg-warning');
        }
        else
        {
             $.messager.alert('Info','Data not selectes !','info');
        }
    }
        
</script>

<style type="text/css">
    .bg-error{ 
        background: red;
    }
    .bg-error .panel-title{
        color:#fff;
    }
    .bg-warning{ 
        background: yellow;
    }
    .bg-warning .panel-title{
        color:#000;
    }
    #fm-master_employee{
        margin:0;
        padding:10px 30px;
    }
    #fm-master_employee-upload{
        margin:0;
        padding:10px 30px;
    }
    .ftitle{
        font-size:14px;
        font-weight:bold;
        padding:5px 0;
        margin-bottom:10px;
        border-bottom:1px solid #ccc;
    }
    .fitem{
        margin-bottom:5px;
    }
    .fitem label{
        display:inline-block;
        width:100px;
    }
    .fitem input{
        display:inline-block;
        width:150px;
    }
</style>


<!-- ----------- -->
<div id="dlg-master_employee" class="easyui-dialog" style="width:600px; height:500px; padding: 10px 20px" closed="true" buttons="#dlg-buttons-master_employee">
    <form id="fm-master_employee" method="post" novalidate>        
        <div class="fitem">
            <label for="type">ID</label>
            <input type="text" id="m_employee_id" name="m_employee_id" class="easyui-numberbox" required="true"/>
        </div>
        <div class="fitem">
            <label for="type">Name</label>
            <input type="text" id="m_employee_name" name="m_employee_name" class="easyui-textbox" required="true"/>
        </div>
        <div class="fitem">
            <label for="type">Birth</label>
            <input type="text" id="m_employee_birth" name="m_employee_birth" class="easyui-datebox" required="true"/>
        </div>
        <div class="fitem">
            <label for="type">Address</label>
            <input type="text" id="m_employee_addr" name="m_employee_addr" data-options="multiline:true" style="width:300px;height:90px" class="easyui-textbox" required="true"/>
        </div>
        <div class="fitem">
            <label for="type">Department</label>
            <input type="text" id="m_employee_dept" name="m_employee_dept" style="width:150px;" class="easyui-combobox" required="true"
                data-options="url:'<?php echo site_url('master/employee/getDept'); ?>',
                method:'get', valueField:'m_dept_id', textField:'m_dept_name', panelHeight:'150'"/>
        </div>
        <div class="fitem">
            <label for="type">Hired</label>
            <input type="text" id="m_employee_hired" name="m_employee_hired" class="easyui-datebox" required="true"/>
        </div>
        <div class="fitem">
            <label for="type">Termination</label>
            <input type="text" id="m_employee_termit" name="m_employee_termit" class="easyui-datebox" />
        </div>
        <div class="fitem">
            <label for="type">Rank</label>
            <input type="text" id="m_employee_rank" name="m_employee_rank" style="width:150px;" class="easyui-combobox" required="true"
                data-options="url:'<?php echo site_url('master/employee/getRank'); ?>',
                method:'get', valueField:'m_rank_id', textField:'m_rank_name', panelHeight:'150'"/>
        </div>
        <div class="fitem">
            <label for="type">Status</label>
            <input type="text" id="m_employee_status" name="m_employee_status" style="width:150px;" class="easyui-combobox" required="true"
                data-options="url:'<?php echo site_url('master/employee/getStatus'); ?>',
                method:'get', valueField:'m_status_id', textField:'m_status_name', panelHeight:'150'"/>
        </div>
        <div class="fitem">
            <label for="type">Shuttle</label>
            <input type="text" id="m_employee_shuttle" name="m_employee_shuttle" style="width:150px;" class="easyui-combobox" required="true"
                data-options="url:'<?php echo site_url('master/employee/getShuttle'); ?>',
                method:'get', valueField:'m_shuttle_id', textField:'m_shuttle_name', panelHeight:'150'"/>
        </div>
    </form>
</div>

<!-- Dialog Button -->
<div id="dlg-buttons-master_employee">
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-ok" onclick="masterEmployeeSave();">Save</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-cancel" onclick="javascript:$('#dlg-master_employee').dialog('close');">Cancel</a>
</div>

<!-- End of file v_employee.php -->
<!-- Location: ./application/views/master/v_employee.php -->