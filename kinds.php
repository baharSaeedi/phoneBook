<?php  //Browse Form (Vasaeghe Manghool)
session_start(); require_once "config.php";
$item_ax=right(27);
if (!PassTru($item_ax)) echo "<script>window.location='mainpage.php';</script>";
foreach($_GET  as $key => $value) ${$key}=$value;
foreach($_POST as $key => $value) ${$key}=$value;
$FileName="kinds.php";
$TableName="acc_kinds";
$right_ins=0;$right_edt=0;$right_del=0;
if($s__t) $sort=dec_par($s__t);
if($o__g) $org=dec_par($o__g);
if($y__r) $year=dec_par($y__r);
if($i__) $id_=dec_par($i__);
$right_ins=$right_edt=$right_del=1;
echo '
  <html>
  <meta content="text/html; charset=UTF-8" http-equiv=content-type>
  <link href="style.css" type="text/css" rel="stylesheet">
  <body scroll=no topmargin=0 leftmargin=0 marginheight=0 marginwidth=0 dir=rtl>
  <form method=post>';
if ($Mode==Forget) $Mode="";
if ($Search_x)
{$res = $db->query("select * from $TableName where 0") or die("Query failed");
    $Mode="";
    $temp="";
    $i = 0;
    $name=bug($name);
    while ($i < $db->num_fields($res))
    { $meta = $db->fetch_field($res);
        $fldnam=$meta->name;
        $fldtyp=$meta->type;
        if ($fldtyp=='253' or $fldtyp=='250' or $fldtyp=='254' or $fldtyp=='251')
        {if (${$fldnam}) $temp.="and ".$fldnam." like '%".${$fldnam}."%'";}
        else if (${$fldnam})
        {$temp.="and ".$fldnam."='".${$fldnam}."'";}
        $i++;
    }
    if (!$temp) $where_=""; else $where_=$temp;
}
if ($Edit_x)
{$Mode="";
    $res = $db->query("select * from $TableName where 0") or die("Query failed");
    $i = 0;
    $sets="";
    $name=bug($name);
    while ($i < $db->num_fields($res))
    { $meta = $db->fetch_field($res);
        $fldnam=$meta->name;
        if ($fldnam!='year' and $fldnam!='id') $sets.="$fldnam='${$fldnam}',";
        $i++;
    }
    $sets=substr($sets,0,strlen($sets)-1);
    $Query="Update $TableName set $sets where dbn='$dbn' and org='$acc_org_id' and year='$AccActiveYear' and id='$id_'";
    $cur=$db->query($Query,'Could not update record');
}
if ($Insert_x)
{if (!$name)
    echo '<script>alert("ثبت بدون عنوان امکان پذير نمي باشد");</script>';
else
{ $q2="select * from $TableName where 0";
    $res = $db->query($q2) or die("Query failed1");
    $i = 0;
    $flds_="";
    $vals_="";
    $name=bug($name);
    while ($i < $db->num_fields($res))
    { $meta = $db->fetch_field($res);
        $fldnam=$meta->name;
        $flds_.="$fldnam,";
        $vals_.="'${$fldnam}',";
        $i++;
    }
    $flds_=substr($flds_,0,strlen($flds_)-1);
    $vals_=substr($vals_,0,strlen($vals_)-1);
    $q1="Insert into $TableName (".$flds_.") value (".$vals_.")";
    $cur=$db->query($q1,'Could not insert record');
}
}
if ($Delete_x)
{
    $Mode="";
    $q1="Delete from $TableName where dbn='$dbn' and org='$acc_org_id' and year='$AccActiveYear' and id='$id_'";
    $cur=$db->query($q1,'Could not delete record');
}
require_once "log_master.php";
echo '
   <div align=center>
   <table width="50%" border="0" cellpadding=0 cellspacing=0 style="position:relative;top:10;background-color:#D6D6D6;">
   <tr height="32">
    <td valign=bottom>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
     <tr class="table-header">
      <td width="12px"><img src="images/theme2/rightt-top-table1.jpg" width="12" height="32"/></td>
      <td  width="100%"class="table-header" onclick="window.location=\'my_frame.php\'">
         معرفي انواع عطف در اسناد حسابداري
	  </td>
      <td width="12px"><img src="images/theme2/leftt-top-table1.jpg" width="12" height="32"/></td>
     </tr>
    </table>
    </td>
  </tr>
   <tr>
   <td valign=top>
     <div style="height:'.$height.'px;overflow:auto;width:100%">
     <table align=cener width=100% height=100% cellpadding=0 cellspacing=0 style="overflow:auto">
      <tr class="table-title">
       <td width=10%>کـد</td>
        <td width=65%>نوع عطف</td>
        <td width=10%>حــــذف</td>
      </tr>';
if (($Mode=='Insert' and $right_ins) or $Mode=='Search')
{if ($Mode=='Insert')
{$id=$db->get_from_where("max(id)",$TableName,"dbn='$dbn' and org='$acc_org_id' and year='$AccActiveYear'");$id++;}
    echo '
      <tr class="table-even">
         <td><input class="text04" type="text" name="id" value="'.$id.'" style="width:100%"></td>
         <td><input class="text04" type="text" name="name" value="'.$name.'" style="width:100%"></td>
	     <td>';
    if ($Mode=='Search') echo '
          <input type="submit" name="Search_x" class="button" value="جستجو">';
    else echo '
          <input type="submit" name="Insert_x" class="button" value="ذخيره">';
    echo '
          <input class="button" type="button" value="انصراف" style="cursor:pointer;" onClick="window.location=\''.enc_par_me(''.$Filename.'?sort='.$sort.'').'\'">
         </td>
	    </tr>';
}
$q1="Select * from $TableName where dbn='$dbn' and org='$acc_org_id' and year='$AccActiveYear' $where_ order by id";
$cur1=$db -> query($q1,'');
$i=1;
while ($row1=$db -> fetch_array($cur1))
{$edtClick='';if ($right_edt) $edtClick='onClick="window.location=\''.enc_par_me(''.$FileName.'?Mode=Edit&org='.$acc_org_id.'&year='.$AccActiveYear.'&id_='.$row1["id"].'').'\'"';
    if($i++%2) echo '
        <tr class="table-odd" onMouseOver="this.style.background=\'#f0ee8d\'" onMouseOut="this.style.background=\'#d7e2ee\'">';
    else echo '
        <tr class="table-even" onMouseOver="this.style.background=\'#f0ee8d\'" onMouseOut="this.style.background=\'#f3f3f3\'">';
    if ($Mode=='Edit' and $row1['id']=="$id_" and $right_edt)
    {echo '
          <input type=hidden name=org value='.$acc_org_id.'>
          <input type=hidden name=year value='.$AccActiveYear.'>
		  <td><input type="text" class="text04" size=10 name="mk" value="'.$row1['id'].'" style="width:100%"></td>
          <td><input type="text" class="text04"  size=25 name="name" value="'.$row1['name'].'" style="width:100%"></td>
          <td>
           <input class="button" type="submit" name="Edit_x" value="اصلاح" style="cursor:pointer" >
           <input class="button" type="button" value="انصراف" style="cursor:pointer" onClick="window.location=\''.enc_par_me(''.$Filename.'?sort='.$sort.'').'\'">
          </td>';
    }
    else
    {echo '
          <td nowrap style="cursor:pointer" '.$edtClick.'>'.$row1["id"].'</td>
          <td nowrap style="cursor:pointer" '.$edtClick.'>'.$row1["name"].'</td>
		  <td  style="cursor:pointer;"><Img src="./images/theme2/delete1.png" border=0'; if ($right_del) echo ' onClick="iconfirm(\''.enc_par_me(''.$FileName.'?Delete_x=1&org='.$acc_org_id.'&year='.$AccActiveYear.'&id_='.$row1["id"].'').'\',\'براي حذف اين عطف\')"'; echo '></td>';
    }
    echo '
        </tr>';
}
for ($j=$i; $j<=$rowpage; $j++)
{ if($j%2) echo '
		<tr class="table-odd" >';
else echo '
		<tr class="table-even" >';
    echo '
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
       </tr>';
}
echo '
      </table>
      </div>
     </td>
    </tr>
   <tr class="table-footer" height="39">
    <td>
     <table width=100% border=0 dir=rtl cellpadding=0 cellspacing=0>
      <tr>
       <td width="12">
        <img src="images/theme2/right-bottom-table1.jpg" width="12" height="39"/></td>
	   <td width=100% align=center>';
if (!$Mode) {
    echo '
	    <table name=local1 width=100% dir=rtl border=0>
		 <tr>
          <td  width=85px style="cursor:pointer" title="جستجوي اسناد" align=left>
           <img border="0" src="images/theme2/search.png" width="81" height="32" style="cursor:pointer" 
           onClick="window.location=\''.$FileName.'?Mode=Search\'"
		   onmouseOver="this.src=\'images/theme2/acc-search-hover.png\'"
           onmouseOut="this.src=\'images/theme2/search.png\'"></td>';
    if ($right_ins) echo '
           <td title="افزودن ركورد" align=center width=85px>
            <img border="0" src="images/theme2/new.png" width="81" height="32" style="cursor:pointer" 
            onClick="window.location=\''.enc_par_me(''.$FileName.'?Mode=Insert&org='.$acc_org_id.'&year='.$AccActiveYear.'').'\'"
			onmouseOver="this.src=\'images/theme2/acc-new-hover.png\'"
            onmouseOut="this.src=\'images/theme2/new.png\'"></td>';
    echo '
           <td  width=85px style="cursor:pointer" title="بازگشت به منوي اصلي" salign=center>
            <img border="0" src="images/theme2/back.png" width="81" height="32" style="cursor:pointer" 
            onClick="window.location=\'my_frame.php\'"
			onmouseOver="this.src=\'images/theme2/acc-back-hover.png\'"
            onmouseOut="this.src=\'images/theme2/back.png\'"></td>
			<td>&nbsp;</td>
		  </tr>
		 </table>
		</td>';
}
echo '
	    <td width="12">
         <img src="images/theme2/left-bottom-table1.jpg" width="12" height="39"/></td>
       </tr>
      </table>';
echo '
    </td>
   </tr>
   </table>
   </div>
  </form>
  </body>
  </html>
  ';
?>
