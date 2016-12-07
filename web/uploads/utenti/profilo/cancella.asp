<!--#include file="include/_db.asp"-->
<%
referr= Request.ServerVariables("HTTP_REFERER")
dim fs
set fs = Server.CreateObject("Scripting.FileSystemObject")
fs.deleteFile Server.MapPath(request.querystring("img"))
set fs = nothing
set objFSO = nothing
set objFile = nothing
%>
<%
Dim sql
Set connessione = Server.CreateObject("ADODB.Connection") 
connessione.Open "DRIVER={Microsoft Access Driver (*.mdb)};DBQ=" & DbU
sql = "UPDATE utenti SET avart='' WHERE avart='"&request.querystring("img")&"'"
connessione.Execute(sql) 
connessione.close
Set connessione = Nothing 

Response.Redirect referr 
%>

