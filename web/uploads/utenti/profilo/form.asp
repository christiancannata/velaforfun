<%@ Language=VBScript %> 
<!--#include virtual="/public/forum/include/_db.asp"-->
<!--#include virtual="/public/forum/include/_config.asp"-->
<html>
<head>
<!--#include virtual="/public/forum/include/_varie.asp"-->
<TITLE><%=Pag%> - <%=NOMEFORUM%></TITLE>
<style>
td{font-family:verdana;font-size:9px;font-weight;color:#000000;}
select{font-family:verdana;font-size:10px;font-weight:bold;color:000000;}
input{font-family:verdana;font-size:10px;font-weight:bold;color:000000;}
textarea{font-family:verdana;font-size:10px;font-weight:bold;color:000000;}
a:link{font-family:verdana;font-size:10px;font-weight:bold;color:000000;text-decoration:none;}
a:visited{font-family:verdana;font-size:10px;font-weight:bold;color:000000;text-decoration:none;}
a:active{font-family:verdana;font-size:10px;font-weight:bold;color:000000;text-decoration:none;}
a:hover{font-family:verdana;font-size:10px;font-weight:bold;color:#3366CC;text-decoration:none;}
.titoli{font-family:arial;font-size:12px;font-weight:bold;color:#000000;}

.titoli:link{font-family:arial;font-size:12px;font-weight:bold;color:#000000;text-decoration:none;}
.titoli:visited{font-family:arial;font-size:12px;font-weight:bold;color:#000000;text-decoration:none;}
.titoli:active{font-family:arial;font-size:12px;font-weight:bold;color:#000000;text-decoration:none;}
.titoli:hover{font-family:arial;font-size:12px;font-weight:bold;color:#3366CC;text-decoration:none;}
</style>

<script language="javascript"> 
function controlla() 
{ 
bottone.innerHTML="<input type='Submit' Disabled value='ATTENDI!'><br><b><font face=arial size=2 color='darkred'>STO LAVORANDO...</font></b><br><br>"; 
} 
</script> 
</head>

<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" onLoad="if (self != top) top.location = self.location">

<table border="0" cellspacing="1" cellpadding="0" width="100%" bgcolor="<%=fdue%>">
<tr>
<td bgcolor="<%=fdue%>">
<%
session ("username")= request.querystring("user")
%>
<br><br>
<center><span class="titoli">MODIFICA IL TUO PROFILO</span></center><br><br>
<div align="center">

<FORM METHOD="Post" ENCTYPE="multipart/form-data" ACTION="outputFile.asp" OnSubmit="controlla()">
<font face="Verdana" size="2">
File : <INPUT TYPE="file" NAME="blob"><BR>
<div id="bottone" align=center>
<INPUT TYPE="submit" NAME="Enter">
</div>

</FORM><br><br><br><br>
<font size=1>Il tempo necessario aumenta proporzionalmente al peso dell'immagine.<br>
max 10 kb. Estensioni jpg, jpeg, gif, png.<br>
utente: <%=session ("username")%></font>
</td>
</tr>
</table>
</BODY>
</HTML>
