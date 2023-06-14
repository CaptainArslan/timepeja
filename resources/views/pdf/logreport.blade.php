<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Log Report</title>
    <link rel="stylesheet" href="{{ asset('css/pdf.css') }}" media="all" />
</head>

<body>
    <header class="clearfix">
        <div id="logo">
            <img src="{{ asset('images/logo.png') }}">
        </div>
        <h1>Log Report (Driver)</h1>
        <!-- <div id="company" class="clearfix">
            <div>Company Name</div>
            <div>455 Foggy Heights,<br /> AZ 85004, US</div>
            <div>(602) 519-0450</div>
            <div><a href="mailto:company@example.com">company@example.com</a></div>
        </div> -->
        <div id="project">
            <div><span>NAME:</span> Website development</div>
            <div><span>ADDRESS:</span> 796 Silver Harbour, TX 79273, US</div>
            <div><span>EMAIL:</span> <a href="mailto:john@example.com">john@example.com</a></div>
            <div><span>FROM DATE:</span> August 17, 2015</div>
            <div><span>TO DATE:</span> September 17, 2015</div>
        </div>
    </header>
    <main>
        <table>
            <thead>
                <tr>
                    <th class="service">Date</th>
                    <th class="desc">SCHEDULE TIME</th>
                    <th>DRIVER</th>
                    <th>VEHICLE</th>
                    <th>ROUTE NO</th>
                    <th>ACTUIAL START/END TIME</th>
                    <th>TRIP STATUS</th>
                    <th>STATUS</th>
                    <th>DELAY REASON</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="service">Design</td>
                    <td class="desc">Creating a recognizable design solution based on the company's existing visual identity</td>
                    <td class="unit">$40.00</td>
                    <td class="qty">26</td>
                    <td class="total">$1,040.00</td>
                    <td>01:31 PM / 03:34 PM</td>
                    <td>completed</td>
                    <td>On-Time</td>
                    <td>Lorem ipsum dolor sit amet.</td>
                </tr>
                <tr>
                    <td class="service">Development</td>
                    <td class="desc">Developing a Content Management System-based Website</td>
                    <td class="unit">$40.00</td>
                    <td class="qty">80</td>
                    <td class="total">$3,200.00</td>
                    <td>01:31 PM / 03:34 PM</td>
                    <td>completed</td>
                    <td>On-Time</td>
                    <td>Lorem ipsum dolor sit amet.</td>
                </tr>
                <tr>
                    <td class="service">SEO</td>
                    <td class="desc">Optimize the site for search engines (SEO)</td>
                    <td class="unit">$40.00</td>
                    <td class="qty">20</td>
                    <td class="total">$800.00</td>
                    <td>01:31 PM / 03:34 PM</td>
                    <td>completed</td>
                    <td>On-Time</td>
                    <td>Lorem ipsum dolor sit amet.</td>
                </tr>
                <tr>
                    <td class="service">Training</td>
                    <td class="desc">Initial training sessions for staff responsible for uploading web content</td>
                    <td class="unit">$40.00</td>
                    <td class="qty">4</td>
                    <td class="total">$160.00</td>
                    <td>01:31 PM / 03:34 PM</td>
                    <td>completed</td>
                    <td>On-Time</td>
                    <td>Lorem ipsum dolor sit amet.</td>
                </tr>
            </tbody>
        </table>
    </main>
    <footer>
        Invoice was created on a computer and is valid without the signature and seal.
    </footer>
</body>

</html>