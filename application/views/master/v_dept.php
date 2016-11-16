<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<!-- Data Grid -->
<table id="grid-master_dept"
    data-options="pageSize:50, multiSort:true, remoteSort:true, rownumbers:true, singleSelect:true, 
                fit:true, fitColumns:true, toolbar:toolbar_master_dept">
    <thead>
        <tr>
            <th data-options="field:'ck',checkbox:true" ></th>
            <th data-options="field:'m_dept_id'"    width="200" align="center" sortable="true">Dept Id</th>
            <th data-options="field:'m_dept_name'"  width="400" halign="center" align="left" sortable="true">Dept. Name</th>
        </tr>
    </thead>
</table>

<script type="text/javascript">
    var toolbar_master_dept = [{
        id      : 'master_dept-new',
        text    : 'New',
        iconCls : 'icon-new_file',
        handler : function(){masterDeptCreate();}
    },{
        id      : 'master_dept-edit',
        text    : 'Edit',
        iconCls : 'icon-edit',
        handler : function(){masterDeptUpdate();}
    },{
        id      : 'master_dept-delete',
        text    : 'Delete',
        iconCls : 'icon-cancel',
        handler : function(){masterDeptHapus();}
    },{
        text    : 'Refresh',
        iconCls : 'icon-reload',
        handler : function(){masterDeptRefresh();}
    }];
    
    $('#grid-master_dept').datagrid({
        onLoadSuccess   : function(){
            $('#master_dept-edit').linkbutton('disable');
            $('#master_dept-delete').linkbutton('disable');
        },
        onSelect        : function(){
            $('#master_dept-edit').linkbutton('enable');
            $('#master_dept-delete').linkbutton('enable');
        },
        onClickRow      : function(){
            $('#master_dept-edit').linkbutton('enable');
            $('#master_dept-delete').linkbutton('enable');
        },
        onDblClickRow   : function(){
            masterDeptUpdate();
        },
        view            :scrollview,
        remoteFilter    :true,
        url             :'<?php echo site_url('master/dept/index'); ?>?grid=true'})
    .datagrid('enableFilter');

    function masterDeptRefresh() {
        $('#master_dept-edit').linkbutton('disable');
        $('#master_dept-delete').linkbutton('disable');
        $('#grid-master_dept').datagrid('reload');
    }
    
    function masterDeptCreate() {
        $('#dlg-master_dept').dialog({modal: true}).dialog('open').dialog('setTitle','Add Data');
        $('#fm-master_dept').form('clear');
        url = '<?php echo site_url('master/dept/create'); ?>';
    }
    
    function masterDeptUpdate() {
        var row = $('#grid-master_dept').datagrid('getSelected');
        if(row){
            $('#dlg-master_dept').dialog({modal: true}).dialog('open').dialog('setTitle','Edit Data');
            $('#fm-master_dept').form('load',row);
            url = '<?php echo site_url('master/dept/update'); ?>/' + row.m_dept_id;
        }
        else{
             $.messager.alert('Info','Data not selected !','info');
        }
    }
    
    function masterDeptSave(){
        $('#fm-master_dept').form('submit',{
            url: url,
            onSubmit: function(){
                return $(this).form('validate');
            },
            success: function(result){
                var result = eval('('+result+')');
                if(result.success){
                    $('#dlg-master_dept').dialog('close');
                    masterDeptRefresh();
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
        
    function masterDeptHapus(){
        var row = $('#grid-master_dept').datagrid('getSelected');
        if (row){
            var win = $.messager.confirm('Confirm','Delete Dept. '+row.m_dept_name+' ?',function(r){
                if (r){
                    $.post('<?php echo site_url('master/dept/delete'); ?>',{m_dept_id:row.m_dept_id},function(result){
                        if (result.success){
                            masterDeptRefresh();
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
    #fm-master_dept{
        margin:0;
        padding:10px 30px;
    }
    #fm-master_dept-upload{
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
<div id="dlg-master_dept" class="easyui-dialog" style="width:400px; height:250px; padding: 10px 20px" closed="true" buttons="#dlg-buttons-master_dept">
    <form id="fm-master_dept" method="post" novalidate>        
        <div class="fitem">
            <label for="type">Dept. Name</label>
            <input type="text" id="m_dept_name" name="m_dept_name" class="easyui-textbox" required="true"/>
        </div>
    </form>
</div>

<!-- Dialog Button -->
<div id="dlg-buttons-master_dept">
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-ok" onclick="masterDeptSave();">Save</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-cancel" onclick="javascript:$('#dlg-master_dept').dialog('close');">Cancel</a>
</div>

<!-- End of file v_dept.php -->
<!-- Location: ./application/views/master/v_dept.php -->