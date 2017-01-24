<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Word Statistics</title>

    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>

    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="fonts/css/font-awesome.css">
    <link rel="stylesheet" href="fonts/css/font-awesome.min.css">

    <style>
        body{
            background-image: url("img/words-blog2.jpg");
        }
        hr {
            border: 0;
            height: 1px;
            background: #333;
            background-image: linear-gradient(to right, #ccc, #333, #ccc);
        }
    </style>

    <script type="text/javascript">
		document.getElementById("btnUpload").onclick = function () {
            location.href = "upload_a_file.php";
        };
		
        document.getElementById("btnCompare").onclick = function () {
            location.href = "compare_two_files.php";
        };

        document.getElementById("btnSearchWord").onclick = function () {
            location.href = "search_a_word_in_a_file.php";
        };
    </script>
</head>

<body>
    <nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="home.php">
                    <img src="img/word_stats.png" class="img-responsive" style="margin-top: -5px;">
                </a>
            </div>
        </div>
    </nav>

    <div class="container" style="margin-top: 140px;">
        <div class="row">
            <div class="col-sm-6">
                <img src="img/1.jpg" class="img-responsive">
            </div>

            <div class="col-sm-5" style="background-color: white; padding-top: 20px; padding-bottom: 15px; margin-left: 50px;">
                <h1><b>WORD STATISTICS</b></h1><br>
                <h4>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's
                    standard dummy text ever since the 1500s, when an unknown printer.</h4>
				<h4>Brojac: </h4>
					<br>
                <button class="btn btn-info" type="submit" id="btnUpload" onclick="location.href = 'upload_a_file.php';" style="margin-right: 5px;"><b>Upload a file</b></button>
                <button class="btn btn-success" style="margin-right: 5px;" type="submit" id="btnCompare" onclick="location.href = 'compare_two_files.php';"><b>Compare two files</b></button>
                <button class="btn btn-warning" type="submit" id="btnSearchWord" onclick="location.href = 'search_a_word_in_file.php';"><b>Search word in a file</b></button>
            </div>
        </div>
        <br><br><br><br>
        <div class="row" style="background-color: white;">
            <div class="col-sm-12" style="background-color: white">
                <hr>
                    <h3><b>Supported files</b></h3>
                <hr><br>
            </div>
            <div class="col-sm-3 col-lg-offset-1" style="margin-right: 30px;">
                <img src="img/word1.png" class="img-responsive">
                <h4 class="text-center"><b>DOC File<br>(DOCument file)</b><br><small>Extensions: .doc .docm .docx</small></h4>
                <p class="text-justify">A file created in a version of Microsoft's word processing application prior
                    to Microsoft Office 2007. DOC files use a .DOC extension and differ from text files (.TXT extension)
                    because they contain proprietary codes that must be opened in Word or software that reads the Word
                    format.
                </p>
            </div>
            <div class="col-sm-3" style="margin-right: 30px;">
                <img src="img/txt2.png" class="img-responsive">
                <h4 class="text-center"><b>PDF File<br>(Portable Document Format)</b><br><small>Extension: .pdf</small></h4>
                <p class="text-justify">PDF is also an abbreviation for the Netware Printer Definition File.
                    PDF is a file format that has captured all the elements of a printed
                    document as an electronic image that you can view, navigate, print, or forward to someone else.
                </p>
            </div>
            <div class="col-sm-3">
                <img src="img/pdf2.png" class="img-responsive">
                <h4 class="text-center"><b>TXT File<br>(Text File)</b><br><small>Extension: .txt</small></h4>
                <p class="text-justify">"Text file" refers to a type of container, while plain text refers to a type of
                    content. Text files can contain plain text, but they are not limited to such. At a generic level of
                    description, there are two kinds of computer files: text files and binary files.
                </p><br><br><br>
            </div>
        </div>
    </div><br><br><br>
    <br><br>

    <div class="navbar navbar-fixed-bottom navbar-inverse" style="padding-top: 15px; color: dimgray">
        <p class="text-center">&copy; Copyrights FINKI</p>
    </div>
</body>
</html>