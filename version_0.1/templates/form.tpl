<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML//EN">
<html> <head>
    <title>Data Entry Form for Gerard</title>
</head>

<body>

    <form enctype="multipart/form-data" action="form.php" method="post">
        <table width="100%" cellpadding="4" cellspacing="4">
            <tr>
                <td>
                    Regions:
                </td>
                <td>
                    <select name="regionid">
                        <option value="0"> NULL </option>
                        <!-- BEGIN regionrow -->
                        <option value="{regionid}" {regionselected}> {region} </option>
                        <!-- END regionrow -->
                    </select>
                </td>
                <td>
                    Sub-Regions:
                </td>
                <td>
                    <select name="subregionid">
                        <option value="0"> NULL </option>
                        <!-- BEGIN subregionrow -->
                        <option value="{subregionid}" {subregionselected}> {subregion} </option>
                        <!-- END subregionrow -->
                    </select>
                </td>
                <td>
                    Places:
                </td>
                <td>
                    <select name="placeid">
                        <option value="0"> NULL </option>
                        <!-- BEGIN placerow -->
                        <option value="{placeid}" {placeselected}> {place} </option>
                        <!-- END placerow -->
                    </select>
                </td>
            </tr>
        </table>
        <table width="100%" cellpadding="4" cellspacing="4">
            <tr>
                <td>
                    Monument Name:
                </td>
                <td>
                    <input type="TEXT" name="structurename" size="20" maxlength="50">
                </td>
                <td colspan="4">
                </td>
            </tr>
            <tr>
                <td>
                    Period:
                </td>
                <td>
                    <input type="TEXT" name="builtin" size="20" maxlength="50">
                </td>
                <td colspan="4">
                </td>
            </tr>
            <tr>
                <td>
                    Style:
                </td>
                <td>
                    <input type="TEXT" name="style" size="20" maxlength="50">
                </td>
                <td colspan="4">
                </td>
            </tr>
            <tr>
                <td>
                    Content:
                </td>
                <td colspan="5">
                    <textarea name="content" rows="20" cols="60"></textarea>
                </td>
            </tr>
            <tr>
                <td>
                    Image 1:
                </td>
                <td>
                    <input type="FILE" name="image1">
                </td>
                <td colspan="1">
                </td>
                <td>
                    Image 7:
                </td>
                <td>
                    <input type="FILE" name="image7">
                </td>
                <td colspan="1">
                </td>
            </tr>
            <tr>
                <td>
                    Image 2:
                </td>
                <td>
                    <input type="FILE" name="image2">
                </td>
                <td colspan="1">
                </td>
                <td>
                    Image 8:
                </td>
                <td>
                    <input type="FILE" name="image8">
                </td>
                <td colspan="1">
                </td>
            </tr>
            <tr>
                <td>
                    Image 3:
                </td>
                <td>
                    <input type="FILE" name="image3">
                </td>
                <td colspan="1">
                </td>
                <td>
                    Image 9:
                </td>
                <td>
                    <input type="FILE" name="image9">
                </td>
                <td colspan="1">
                </td>
            </tr>
            <tr>
                <td>
                    Image 4:
                </td>
                <td>
                    <input type="FILE" name="image4">
                </td>
                <td colspan="1">
                </td>
                <td>
                    Image 10:
                </td>
                <td>
                    <input type="FILE" name="image10">
                </td>
                <td colspan="1">
                </td>
            </tr>
            <tr>
                <td>
                    Image 5:
                </td>
                <td>
                    <input type="FILE" name="image5">
                </td>
                <td colspan="1">
                </td>
                <td>
                    Image 11:
                </td>
                <td>
                    <input type="FILE" name="image11">
                </td>
                <td colspan="1">
                </td>
            </tr>
            <tr>
                <td>
                    Image 6:
                </td>
                <td>
                    <input type="FILE" name="image6">
                </td>
                <td colspan="1">
                </td>
                <td>
                    Image 12:
                </td>
                <td>
                    <input type="FILE" name="image12">
                </td>
                <td colspan="1">
                </td>
            </tr>
        </table>
        <table width="50%" cellpadding="4" cellspacing="4">
            <tr>
                <td align="center">
                    <input type="RESET" value="RESET"">
                </td>
                <td align="center">
                    <input type="hidden" value="submitpressed" name="submitpressed">
                    <input type="SUBMIT" name="Submit" value="Submit">
                </td>
            </tr>
        </table>
    </form>




    <hr>
    <address><a href="http://www.plusthought.org">Synapse</a></address>
    <!-- hhmts start -->
    Last modified: Mon Sep  1 17:12:29 IST 2003
    <!-- hhmts end -->
</body> </html>
