<!doctype html>
<html lang="en" prefix="og: http://ogp.me/ns#">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta property="og:title" content="Windeschedule - ICalendar generator" />
    <meta property="og:description" content="Windeschedule is a web app that makes adding schedules to calendar apps easy! You can create links to schedules for any teacher, class or subject."/>
    <meta property="og:type" content="text/calendar" />
    <meta property="og:url" content="{{ url() }}" />
    <meta property="og:image" content="{{ url() . "/ogp-preview.png" }}" />
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-86486502-2"></script>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
    <!-- Bootstrap core CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <!-- Material Design Bootstrap -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.8.11/css/mdb.min.css" rel="stylesheet">
    <title>Windeschedule - iCalendar generator</title>
</head>
<body class="d-flex">
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12 offset-sm-0 col-md-8 offset-md-2 col-lg-8 offset-lg-2 col-xl-4 offset-xl-4">
            <div class="card card-coming-soon">
                <div class="card-body">
                    <div class="border-bottom">
                        <h3 class="text-center card-title">Schedule selector coming soon&trade;</h3>
                        <h6 class="text-center card-subtitle mb-2 text-muted">Create the links yourself for now...</h6>
                    </div>
                    <div class="card-content">
                        <div class="pb-2 mb-2 border-bottom">
                            <span>URL structure:</span><br>
                            <kbd>"{{ url() }}/schedule/[type]/[code]"</kbd><br><br>
                            <span>Examples:</span><br>
                            <span>Class:</span>
                            <a href="{{ url() }}/schedule/class/ICTSE1e"><samp>{{ url() }}/schedule/class/ICTSE1e</samp></a><br>
                            <span>Teacher:</span>
                            <a href="{{ url() }}/schedule/teacher/BNH09"><samp>{{ url() }}/schedule/teacher/BNH09</samp></a><br>
                            <span>Subject:</span>
                            <a href="{{ url() }}/schedule/subject/ICT.IDS.NW2.V19"><samp>{{ url() }}/schedule/subject/ICT.IDS.NW2.V19</samp></a><br>
                        </div>
                        <div>
                            <span>
                                Windeschedule is a web app that makes adding schedules to calendar apps easy!
                                You can create links to schedules for any teacher, class or subject,
                                check out the following links if you don't know how to add these schedules to
                                <a target="_blank" href="https://support.office.com/en-us/article/import-or-subscribe-to-a-calendar-in-outlook-on-the-web-503ffaf6-7b86-44fe-8dd6-8099d95f38df">Outlook</a>
                                or
                                <a target="_blank" href="https://support.google.com/calendar/answer/37100?co=GENIE.Platform%3DDesktop&hl=en">Google Calendar</a>.
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <span class="text-muted font-small text-left">Spicy memes: <a target="_blank" href="https://windesmemes.nl">Windesmemes</a></span>
                        <span class="text-muted font-small text-right">
                            <a target="_blank" href="https://github.com/roderik3000/windesheim-schedules">Contribute</a>
                            or <a target="_blank" href="https://www.paypal.me/Roderik">Donate</a>!
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    body {
        height: 100vh;
    }

    .card-content {
        margin-top: 1vh;
    }

    .card-coming-soon {
        margin-top: 5vh;
    }
</style>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-86486502-2');
</script>
<!-- JQuery -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<!-- Bootstrap tooltips -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.4/umd/popper.min.js"></script>
<!-- Bootstrap core JavaScript -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>
<!-- MDB core JavaScript -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.8.11/js/mdb.min.js"></script>
</body>
</html>
