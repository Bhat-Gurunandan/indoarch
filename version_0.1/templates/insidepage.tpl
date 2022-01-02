<HTML>
    <HEAD>
        <title>{locationheading}</title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <link href="styles.css" rel="stylesheet" type="text/css">
        <style type="text/css"
         <!--
         .framed {
             margin: 10px;
             border: 1px solid #c0c0c0;
             padding: 2px;
         }
         -->
        </style>
        <script language="JavaScript">
         function OpenImage(linkurl){
             window.open(linkurl, '',
                         "fullscreen=no,toolbar=no,status=no,menubar=no,scrollbars=no,resizable=no,directories=no,location=no,width=400,height=600,top=50,left=40");
         }
        </script>
    </head>
    <body bgcolor="#9DA39D" leftmargin="0" topmargin="0">
        <map name="Map" id="Map">
            <area alt="" coords="29,2,131,16" href="author.php">
            <area alt="About the Book" coords="29,25,127,39" href="index.php">
            <area alt="About the Publisher" coords="29,47,149,63" href="archauto.php">
            <area alt="Start Browsing" coords="28,69,125,85" href="place.php?R=0+S=0+P=0+M=0">
            <area alt="Acknowledgements" coords="29,91,147,106" href="acknowledgements.php">
            <area alt="" coords="29,112,122,128" href="#">
            <area coords="31,135,127,151" href="order.php">
            <area coords="29,157,125,171" href="#">
            <area alt="Contact Us" coords="31,179,105,193" href="contactus.php">
        </map>
        <TABLE WIDTH="780" BORDER="0" CELLPADDING="0" CELLSPACING="0" align="center" bgcolor="#FFFFFF">
            <TABLE WIDTH="780" BORDER="0" CELLPADDING="0" CELLSPACING="0" ALIGN="center" bgcolor="#FFFFFF">
                <TR>
                    <TD valign="top">
                        <TABLE WIDTH="200" BORDER="0" CELLPADDING="0" CELLSPACING="0">
                            <TR>
                                <TD valign="top">
                                    <IMG SRC="images/left_01.jpg" WIDTH="200" HEIGHT="80" ALT=""></TD>
                            </TR>
                            <TR>
                                <TD valign="top">
                                    <IMG SRC="images/left_02.jpg" WIDTH="200" HEIGHT="156" ALT=""></TD>
                            </TR>
                            <TR>
                                <TD valign="top">
                                    <IMG SRC="images/left_03.gif" WIDTH="200" HEIGHT="192" ALT="" BORDER="0" useMap=#Map></TD>
                            </TR>
                            <TR>
                                <TD valign="top">
                                    <IMG SRC="images/blank.gif" WIDTH="200" HEIGHT="42" ALT=""></TD>
                            </TR>
                            <TR>
                                <TD valign="top">
                                    <IMG SRC="images/blank.gif" WIDTH="200" HEIGHT="173" ALT=""></TD>
                            </TR>
                        </TABLE>


                    </TD>
                    <TD valign="top">

                        <TABLE WIDTH="511" BORDER="0" CELLPADDING="0" CELLSPACING="0">
                            <TR>
                                <TD valign="top">
                                    <IMG SRC="images/main_01.gif" WIDTH="511" HEIGHT="29" ALT=""></TD>
                            </TR>
                            <TR>
                                <TD valign="top">
                                    <IMG SRC="images/main_02.gif" WIDTH="511" HEIGHT="51" ALT=""></TD>
                            </TR>
                            <TR>
                                <TD WIDTH="511" HEIGHT="32" valign="top">
                                    <a href="arch_thr_ages.php"><img src="pics/arch_thr_ages.gif" width="120" height="30" alt="Indian Architechture Through the Ages" border="0" align="left"></a>
                                    <a href="intro_arch.php"><img src="pics/intro_arch.gif" width="152" height="30" alt="Introduction to Indian Architechture" border="0" align="left"></a>
                                    <a href="arch_glossary.php"><img src="pics/arch_glossary.gif" width="101" height="30" alt="Architechtural Glossary" border="0" align="left"></a>
                                    <a href="get_around.php"><img src="pics/get_around.gif" width="97" height="30" alt="Getting Around in India" border="0" align="left"></a><br>
                                </TD>
                            </TR>
                            <TR>
                                <TD WIDTH="511" HEIGHT="12" valign="top">
                                    &nbsp;
                                </TD>
                            </TR>
                            <TR>
                                <TD align="right" WIDTH="511" HEIGHT="30" valign="top">
                                    <font face="Arial, Helvetica, sans-serif, Trebuchet MS" size="-1">Browse by cities</font>
                                    <select name="placelink" onChange="location.href='place.php?placelink='+this.options[this.selectedIndex].value">
                                        <option value="R=0+S=0+P=0+M=0">Drop Down and Click to Select</option>
                                        <!-- BEGIN placerow -->
                                        <option value="{placeid}" {placeselected}> {place} </option>
                                        <!-- END placerow -->
                                    </select>
                                </td>
                                <td width="69" height="32" valign="top"></td>
                            </tr>
                            <TR>
                                <TD WIDTH="511" HEIGHT="12" valign="top">
                                    &nbsp;
                                </TD>
                            </TR>
                    </TD>
                </TR>
                <TR>
                    <TD WIDTH="511" HEIGHT="29" valign="top">
                        <img src="images/{locationtitle}" width="511" height="29">
                    </TD>
                </TR>
                <TR>
                    <TD height="523" valign="top" valign="top">

                        <table cellpadding="0" cellspacing="0" border="0" width="100%">
                            <tr>
                                <td valign="top">
                                    <p><a href="place.php?placelink={uplink}"><b>{uplinkaccompanyingtext}</b></a></p>
                                </td>
                            </tr>
                            <tr>
                                <td height="15" valign="top">&nbsp;
                                </td>
                            </tr>
                            <tr>
                                <td valign="top">
                                    <b>{locationheading}</b>
                                </td>
                            </tr>
                            <!-- BEGIN location -->
                            <tr>
                                <td valign="top">
                                    <a href="place.php?placelink={locationlink}">{locationname}</a><br>
                                </td>
                            </tr>
                            <!-- END location -->
                            <tr>
                                <td valign="top" height="20">&nbsp;
                                </td>
                            </tr>
                            <tr>
                                <td valign="top">
                                    <table width="510" cellspacing="2" cellpadding="" border="0">
                                        <tr>
                                            <td rowspan={imagecount} valign="top">
                                                {body}
                                            </td>
                                            <td valign="top">
                                                <a href='javascript:OpenImage("{bigimage0}")'>{image0}</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td valign="top">
                                                {imagecaption0}
                                            </td>
                                        </tr>
                                        <!-- BEGIN imagerow -->
                                        <tr>
                                            <td valign="top">
                                                <a href='javascript:OpenImage("{bigimage}")'>{image}</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td valign="top">
                                                {imagecaption}
                                            </td>
                                        </tr>
                                        <!-- END imagerow -->
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </TD>
                </TR>
                <TR>
                    <TD valign="top">
                        <IMG SRC="images/main_07.gif" WIDTH="511" HEIGHT="26" ALT=""></TD>
                </TR>
                        </TABLE>


                    </TD>
                    <TD valign="top">

                        <TABLE WIDTH="69" BORDER="0" CELLPADDING="0" CELLSPACING="0">
                            <TR>
                                <TD valign="top">
                                    <IMG SRC="images/right_01.gif" WIDTH="69" HEIGHT="80" ALT=""></TD>
                            </TR>
                            <TR>
                                <TD valign="top">
                                    <IMG SRC="images/blank.gif" WIDTH="69" HEIGHT="640" ALT=""></TD>
                            </TR>
                        </TABLE>

                    </TD>
                </TR>
            </TABLE>
    </BODY>
</HTML>
