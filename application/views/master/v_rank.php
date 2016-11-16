<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<!-- Data Grid -->
<table id="grid-master_rank"
    data-options="pageSize:50, multiSort:true, remoteSort:true, rownumbers:true, singleSelect:true, 
                fit:true, fitColumns:true, toolbar:toolbar_master_rank">
    <thead>
        <tr>
            <th data-options="field:'ck',checkbox:true" ></th>
            <th data-options="field:'m_rank_id'"    width="200" align="center" sortable="true">Rank Id</th>
            <th data-options="field:'m_rank_name'"  width="400" halign="center" align="left" sortable="true">Rank. Name</th>
        </tr>
    </thead>
</table>

<script type="text/javascript">
    var toolbar_master_rank = [{
        id      : 'master_rank-new',
        text    : 'New',
        iconCls : 'icon-new_file',
        handler : function(){masterRankCreate();}
    },{
        id      : 'master_rank-edit',
        text    : 'Edit',
        iconCls : 'icon-edit',
        handler : function(){masterRankUpdate();}
    },{
        id      : 'master_rank-delete',
        text    : 'Delete',
        iconCls : 'icon-cancel',
        handler : function(){masterRankHapus();}
    },{
        text    : 'Refresh',
        iconCls : 'icon-reload',
        handler : function(){masterRankRefresh();}
    }];
    
    $('#grid-master_rank').datagrid({
        onLoadSuccess   : function(){
            $('#master_rank-edit').linkbutton('disable');
            $('#master_rank-delete').linkbutton('disable');
        },
        onSelect        : function(){
            $('#master_rank-edit').linkbutton('enable');
            $('#master_rank-delete').linkbutton('enable');
        },
        onClickRow      : function(){
            $('#master_rank-edit').linkbutton('enable');
            $('#master_rank-delete').linkbutton('enable');
        },
        onDblClickRow   : function(){
            masterRankUpdate();
        },
        view            :scrollview,
        remoteFilter    :true,
        url             :'<?php echo site_url('master/rank/index'); ?>?grid=true'})
    .datagrid('enableFilter');

    function masterRankRefresh() {
        $('#master_rank-edit').linkbutton('disable');
        $('#master_rank-delete').linkbutton('disable');
        $('#grid-master_rank').datagrid('reload');
    }
    
    function masterRankCreate() {
        $('#dlg-master_rank').dialog({modal: true}).dialog('open').dialog('setTitle','Add Data');
        $('#fm-master_rank').form('clear');
        url = '<?php echo site_url('master/rank/create'); ?>';
    }
    
    function masterRankUpdate() {
        var row = $('#grid-master_rank').datagrid('getSelected');
        if(row){
            $('#dlg-master_rank').dialog({modal: true}).dialog('open').dialog('setTitle','Edit Data');
            $('#fm-master_rank').form('load',row);
            url = '<?php echo site_url('master/rank/update'); ?>/' + row.m_rank_id;
        }
        else{
             $.messager.alert('Info','Data not selected !','info');
        }
    }
    
    function masterRankSave(){
        $('#fm-master_rank').form('submit',{
            url: url,
            onSubmit: function(){
                return $(this).form('validate');
            },
            success: function(result){
                var result = eval('('+result+')');
                if(result.success){
                    $('#dlg-master_rank').dialog('close');
                    masterRankRefresh();
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
        
    function masterRankHapus(){
        var row = $('#grid-master_rank').datagrid('getSelected');
        if (row){
            var win = $.messager.confirm('Confirm','Delete Rank. '+row.m_rank_name+' ?',function(r){
                if (r){
                    $.post('<?php echo site_url('master/rank/delete'); ?>',{m_rank_id:row.m_rank_id},function(result){
                        if (result.success){
                            masterRankRefresh();
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
    #fm-master_rank{
        margin:0;
        padding:10px 30px;
    }
    #fm-master_rank-upload{
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
<div id="dlg-master_rank" class="easyui-dialog" style="width:400px; height:250px; padding: 10px 20px" closed="true" buttons="#dlg-buttons-master_rank">
    <form id="fm-master_rank" method="post" novalidate>        
        <div class="fitem">
            <label for="type">Rank. Name</label>
            <input type="text" id="m_rank_name" name="m_rank_name" class="easyui-textbox" required="true"/>
        </div>
    </form>
</div>

<!-- Dialog Button -->
<div id="dlg-buttons-master_rank">
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-ok" onclick="masterRankSave();">Save</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="width:75" iconCls="icon-cancel" onclick="javascript:$('#dlg-master_rank').dialog('close');">Cancel</a>
</div>

<!-- End of file v_rank.php -->
<!-- Location: ./application/views/master/v_rank.php -->