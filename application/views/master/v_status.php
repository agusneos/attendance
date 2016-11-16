<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<!-- Data Grid -->
<table id="grid-master_status"
    data-options="pageSize:50, multiSort:true, remoteSort:true, rownumbers:true, singleSelect:true, 
                fit:true, fitColumns:true, toolbar:toolbar_master_status">
    <thead>
        <tr>
            <th data-options="field:'ck',checkbox:true" ></th>
            <th data-options="field:'m_status_id'"    width="200" align="center" sortable="true">Status Id</th>
            <th data-options="field:'m_status_name'"  width="400" halign="center" align="left" sortable="true">Status. Name</th>
        </tr>
    </thead>
</table>

<script type="text/javascript">
    var toolbar_master_status = [{
        id      : 'master_status-new',
        text    : 'New',
        iconCls : 'icon-new_file',
        handler : function(){masterStatusCreate();}
    },{
        id      : 'master_status-edit',
        text    : 'Edit',
        iconCls : 'icon-edit',
        handler : function(){masterStatusUpdate();}
    },{
        id      : 'master_status-delete',
        text    : 'Delete',
        iconCls : 'icon-cancel',
        handler : function(){masterStatusHapus();}
    },{
        text    : 'Refresh',
        iconCls : 'icon-reload',
        handler : function(){masterStatusRefresh();}
    }];
    
    $('#grid-master_status').datagrid({
        onLoadSuccess   : function(){
            $('#master_status-edit').linkbutton('disable');
            $('#master_status-delete').linkbutton('disable');
        },
        onSelect        : function(){
            $('#master_status-edit').linkbutton('enable');
            $('#master_status-delete').linkbutton('enable');
        },
        onClickRow      : function(){
            $('#master_status-edit').linkbutton('enable');
            $('#master_status-delete').linkbutton('enable');
        },
        onDblClickRow   : function(){
            masterStatusUpdate();
        },
        view            :scrollview,
        remoteFilter    :true,
        url             :'<?php echo site_url('master/status/index'); ?>?grid=true'})
    .datagrid('enableFilter');

    function masterStatusRefresh() {
        $('#master_status-edit').linkbutton('disable');
        $('#master_status-delete').linkbutton('disable');
        $('#grid-master_status').datagrid('reload');
    }
    
    function masterStatusCreate() {
        $('#dlg-master_status').dialog({modal: true}).dialog('open').dialog('setTitle','Add Data');
        $('#fm-master_status').form('clear');
        url = '<?php echo site_url('master/status/create'); ?>';
    }
    
    function masterStatusUpdate() {
        var row = $('#grid-master_status').datagrid('getSelected');
        if(row){
            $('#dlg-master_status').dialog({modal: true}).dialog('open').dialog('setTitle','Edit Data');
            $('#fm-master_status').form('load',row);
            url = '<?php echo site_url('master/status/update'); ?>/' + row.m_status_id;
        }
        else{
             $.messager.alert('Info','Data not selected !','info');
        }
    }
    
    function masterStatusSave(){
        $('#fm-master_status').form('submit',{
            url: url,
            onSubmit: function(){
                return $(this).form('validate');
            },
            success: function(result){
                var result = eval('('+result+')');
                if(result.success){
                    $('#dlg-master_status').dialog('close');
                    masterStatusRefresh();
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
        
    function masterStatusHapus(){
        var row = $('#grid-master_status').datagrid('getSelected');
        if (row){
            var win = $.messager.confirm('Confirm','Delete Status. '+row.m_status_name+' ?',function(r){
                if (r){
                    $.post('<?php echo site_url('master/status/delete'); ?>',{m_status_id:row.m_status_id},function(result){
                        if (result.success){
                            masterStatusRefresh();
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
    #fm-master_status{
        margin:0;
        padding:10px 30px;
    }
    #fm-master_status-upload{
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
<div id="dlg-master_status" class="easyui-dialog" style="width:400px; height:250px; padding: 10px 20px" closed="true" buttons="#dlg-buttons-master_status">
    <form id="fm-master_status" method="post" novalidate>        
        <div class="fitem">
            <label for="type">Status. Name</label>
            <input type="text" id="m_status_name" name="m_status_name" class="easyui-textbox" required="true"/>
        </div>
    </form>
</div>

<!-- Dialog Button -->
<div id="dlg-buttons-master_status">
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-ok" onclick="masterStatusSave();">Save</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-cancel" onclick="javascript:$('#dlg-master_status').dialog('close');">Cancel</a>
</div>

<!-- End of file v_status.php -->
<!-- Location: ./application/views/master/v_status.php -->