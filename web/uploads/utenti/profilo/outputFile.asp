  <!--#include virtual="/public/forum/avart/upload.asp"-->
<%Response.Expires=0
  Response.Buffer = TRUE
  Response.Clear
  byteCount = Request.TotalBytes
  RequestBin = Request.BinaryRead(byteCount)
  Dim UploadRequest
  Set UploadRequest = CreateObject("Scripting.Dictionary")
  BuildUploadRequest  RequestBin
  contentType = UploadRequest.Item("blob").Item("ContentType")
  filepathname = UploadRequest.Item("blob").Item("FileName")
  filename = Right(filepathname,Len(filepathname)-InstrRev(filepathname,"\"))
  value = UploadRequest.Item("blob").Item("Value")

  'Create FileSytemObject Component
  Set ScriptObject = Server.CreateObject("Scripting.FileSystemObject")

dim arrayParole
arrayParole=split(filename,".")

  'Create and Write to a File
  pathEnd = Len(Server.mappath(Request.ServerVariables("PATH_INFO")))-14
  Set MyFile = ScriptObject.CreateTextFile(Left(Server.mappath(Request.ServerVariables("PATH_INFO")),pathEnd) & session ("username") &"."& arrayParole(1))
 
  For i = 1 to LenB(value)
	 MyFile.Write chr(AscB(MidB(value,i,1)))
  Next
  MyFile.Close%>
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
</head>

<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" onLoad="if (self != top) top.location = self.location">

<table border="0" cellspacing="1" cellpadding="0" width="100%" bgcolor="<%=Cbordo%>">
<tr>
<%
dim objFSO, objfile, dimensione, ext

Set objFSO = Server.CreateObject("Scripting.FileSystemObject")
Set objFile = objFSO.GetFile(Server.MapPath(session ("username") &"."& arrayParole(1)))
dimensione = objFile.Size
if arrayParole(1)="jpg" OR arrayParole(1)="JPG" OR arrayParole(1)="gif" OR arrayParole(1)="GIF" OR arrayParole(1)="png" OR arrayParole(1)="PNG" OR arrayParole(1)="jpeg" OR  arrayParole(1)="JPEG" then
ext=0
else
ext=1
end if
if dimensione>10000 or ext=1 then 
dim fs
set fs = Server.CreateObject("Scripting.FileSystemObject")
fs.deleteFile Server.MapPath(session ("username") &"."& arrayParole(1))
set fs = nothing
set objFSO = nothing
set objFile = nothing
%>
<td bgcolor="<%=fdue%>">
<center><br><br><br><br><span class="titoli">ERRORE</span><br>
<font size=1><br><br><br><br>
- L'immagine inviata supera le dimensioni consentite (10000 k) [dimesione file: <%=dimensione%> k]<br>
- oppure ha un'estensione non valida (consentite gif, jpg, jpeg, png) [est: <%=arrayParole(1)%>]
<br><br><br><br><br><br>
<a href="form.asp?user=<%=session("username")%>">INVIA UN'ALTRA IMMAGINE</a></font>
<center>
<%

else

Dim sql
Set connessione = Server.CreateObject("ADODB.Connection") 
connessione.Open "DRIVER={Microsoft Access Driver (*.mdb)};DBQ=" & DbU 
sql = "UPDATE utenti SET avart='"&session ("username")&"."&arrayparole(1)&"' WHERE username='"&session ("username")&"'"
connessione.Execute(sql) 
connessione.close
Set connessione = Nothing 
%>
<td bgcolor="<%=fdue%>" WIDTH="50%">
<center><br><br><br><br><span class="titoli">FILE RICEVUTO</span><br>
<font size=1><br><br><br><br>L'immagine è stata salvata correttamente (controlla se si vede a Dx)</font><br><br><br><br><br><br><br><br><br>
<td><td bgcolor="<%=fdue%>" WIDTH="50%">
<center><IMG SRC="<%=(session ("username") &"."& arrayParole(1))%>" border=0 ALT="AVATAR"></center>
<%
end if

%>
</td>
</tr>
</table>
</BODY>
</HTML>

