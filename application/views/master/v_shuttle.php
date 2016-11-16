<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<!-- Data Grid -->
<table id="grid-master_shuttle"
    data-options="pageSize:50, multiSort:true, remoteSort:true, rownumbers:true, singleSelect:true, 
                fit:true, fitColumns:true, toolbar:toolbar_master_shuttle">
    <thead>
        <tr>
            <th data-options="field:'ck',checkbox:true" ></th>
            <th data-options="field:'m_shuttle_id'"    width="200" align="center" sortable="true">Shuttle Id</th>
            <th data-options="field:'m_shuttle_name'"  width="400" halign="center" align="left" sortable="true">Shuttle. Name</th>
        </tr>
    </thead>
</table>

<script type="text/javascript">
    var toolbar_master_shuttle = [{
        id      : 'master_shuttle-new',
        text    : 'New',
        iconCls : 'icon-new_file',
        handler : function(){masterShuttleCreate();}
    },{
        id      : 'master_shuttle-edit',
        text    : 'Edit',
        iconCls : 'icon-edit',
        handler : function(){masterShuttleUpdate();}
    },{
        id      : 'master_shuttle-delete',
        text    : 'Delete',
        iconCls : 'icon-cancel',
        handler : function(){masterShuttleHapus();}
    },{
        text    : 'Refresh',
        iconCls : 'icon-reload',
        handler : function(){masterShuttleRefresh();}
    }];
    
    $('#grid-master_shuttle').datagrid({
        onLoadSuccess   : function(){
            $('#master_shuttle-edit').linkbutton('disable');
            $('#master_shuttle-delete').linkbutton('disable');
        },
        onSelect        : function(){
            $('#master_shuttle-edit').linkbutton('enable');
            $('#master_shuttle-delete').linkbutton('enable');
        },
        onClickRow      : function(){
            $('#master_shuttle-edit').linkbutton('enable');
            $('#master_shuttle-delete').linkbutton('enable');
        },
        onDblClickRow   : function(){
            masterShuttleUpdate();
        },
        view            :scrollview,
        remoteFilter    :true,
        url             :'<?php echo site_url('master/shuttle/index'); ?>?grid=true'})
    .datagrid('enableFilter');

    function masterShuttleRefresh() {
        $('#master_shuttle-edit').linkbutton('disable');
        $('#master_shuttle-delete').linkbutton('disable');
        $('#grid-master_shuttle').datagrid('reload');
    }
    
    function masterShuttleCreate() {
        $('#dlg-master_shuttle').dialog({modal: true}).dialog('open').dialog('setTitle','Add Data');
        $('#fm-master_shuttle').form('clear');
        url = '<?php echo site_url('master/shuttle/create'); ?>';
    }
    
    function masterShuttleUpdate() {
        var row = $('#grid-master_shuttle').datagrid('getSelected');
        if(row){
            $('#dlg-master_shuttle').dialog({modal: true}).dialog('open').dialog('setTitle','Edit Data');
            $('#fm-master_shuttle').form('load',row);
            url = '<?php echo site_url('master/shuttle/update'); ?>/' + row.m_shuttle_id;
        }
        else{
             $.messager.alert('Info','Data not selected !','info');
        }
    }
    
    function masterShuttleSave(){
        $('#fm-master_shuttle').form('submit',{
            url: url,
            onSubmit: function(){
                return $(this).form('validate');
            },
            success: function(result){
                var result = eval('('+result+')');
                if(result.success){
                    $('#dlg-master_shuttle').dialog('close');
                    masterShuttleRefresh();
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
        
    function masterShuttleHapus(){
        var row = $('#grid-master_shuttle').datagrid('getSelected');
        if (row){
            var win = $.messager.confirm('Confirm','Delete Shuttle. '+row.m_shuttle_name+' ?',function(r){
                if (r){
                    $.post('<?php echo site_url('master/shuttle/delete'); ?>',{m_shuttle_id:row.m_shuttle_id},function(result){
                        if (result.success){
                            masterShuttleRefresh();
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
    #fm-master_shuttle{
        margin:0;
        padding:10px 30px;
    }
    #fm-master_shuttle-upload{
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
<div id="dlg-master_shuttle" class="easyui-dialog" style="width:400px; height:250px; padding: 10px 20px" closed="true" buttons="#dlg-buttons-master_shuttle">
    <form id="fm-master_shuttle" method="post" novalidate>        
        <div class="fitem">
            <label for="type">Shuttle. Name</label>
            <input type="text" id="m_shuttle_name" name="m_shuttle_name" class="easyui-textbox" required="true"/>
        </div>
    </form>
</div>

<!-- Dialog Button -->
<div id="dlg-buttons-master_shuttle">
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-ok" onclick="masterShuttleSave();">Save</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-cancel" onclick="javascript:$('#dlg-master_shuttle').dialog('close');">Cancel</a>
</div>

<!-- End of file v_shuttle.php -->
<!-- Location: ./application/views/master/v_shuttle.php -->