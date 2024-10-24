<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <title>Document</title>
</head>
<body>
    <div class="card">
        <div class="card-body">
          <div class="container mb-5 mt-3">
            <div class="row d-flex align-items-baseline">
              <div class="col-xl-12">
                <div class="brand-section">
                <div class="row" style="justify-content: space-between;">
                    <div class="col-4">
                        <h1 class="text-black">Stoppick</h1>
                    </div>
                    <div class="col-4"></div>
                    <div class="col-4">
                        <div class="company-details">
                            <p class="text-black">Stoppick Association </p>
                            <p class="text-black">+92 300-1234567</p>
                        </div>
                    </div>
                </div>
            </div>
                <!-- <p style="color: #7e8d9f;font-size: 20px;">Invoice >> <strong>ID: #123-123</strong></p> -->
              </div>
              <div class="col-xl-3 float-end">
                <a class="btn btn-light text-capitalize border-0" data-mdb-ripple-color="dark"><i
                    class="fas fa-print text-primary"></i> Print</a>
                <a class="btn btn-light text-capitalize" data-mdb-ripple-color="dark"><i
                    class="far fa-file-pdf text-danger"></i> Export</a>
              </div>
              <hr>
            </div>
      
            <div class="container">
              <div class="col-md-12">
                <!-- div class="text-center">
                  <i class="fab fa-mdb fa-4x ms-0" style="color:#5d9fc5 ;"></i>
                  <p class="pt-0">MDBootstrap.com</p>
                </div> -->
      
              </div>
      
      
              <div class="row pt-3">
                <div class="col-xl-12">
                    <div class="h5">(System Unique code) </div>
                  <ul class="list-unstyled">
                    <li class="text-muted">From: <span style="color:#5d9fc5 ;">John Lorem</span></li>
                    <li class="text-muted">To: <span style="color:#5d9fc5 ;">John Lorem</span></li>
                    <li class="text-muted">Email: <span style="color:#5d9fc5 ;">John.Lorem@gmail.com</span></li>
                    <li class="text-muted">Phone: <span style="color:#5d9fc5 ;">123-456-789</span></li>
                  </ul>
                </div>
              </div>
      
              <div class="row my-2 mx-1 justify-content-center">
                <table class="table table-striped table-borderless">
                  <thead style="background-color:#84B0CA ;" class="text-white">
                    <tr>
                      <th scope="col">Date</th>
                      <th scope="col">Scheduled Time</th>
                      <th scope="col">Driver</th>
                      <th scope="col">Actual trip start/ End time</th>
                      <th scope="col">Trip Status</th>
                      <th scope="col">Status</th>
                      <th scope="col">Delay Reason</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <th scope="row">13-06-23</th>
                      <td>02:06</td>
                      <td>ABCD</td>
                      <td>1234 - 4567</td>
                      <td>Nil</td>
                      <td>Nil</td>
                      <td>Nil</td>
                    </tr>
                  </tbody>
      
                </table>
              </div>
           <!--    <div class="row">
                <div class="col-xl-8">
                  <p class="ms-3">Add additional notes and payment information</p>
      
                </div>
                <div class="col-xl-3">
                  <ul class="list-unstyled">
                    <li class="text-muted ms-3"><span class="text-black me-4">SubTotal</span>$1110</li>
                    <li class="text-muted ms-3 mt-2"><span class="text-black me-4">Tax(15%)</span>$111</li>
                  </ul>
                  <p class="text-black float-start"><span class="text-black me-3"> Total Amount</span><span
                      style="font-size: 25px;">$1221</span></p>
                </div>
              </div> -->
              <hr>
              <div class="row">
                <!-- <div class="col-xl-10">
                  <p>Thank you for your purchase</p>
                </div> -->
                <div class="col-12 text-center">
                    <p>&copy; Copyright 2023 - Stoppick. All rights reserved.
                        <br/><a href="{{ route('home') }}" class="">www.stoppick.com</a>
                    </p>
                 </div>
                <!-- <div class="col-xl-2">
                  <button type="button" class="btn btn-primary text-capitalize"
                    style="background-color:#60bdf3 ;">Pay Now</button>
                </div> -->
              </div>
      
            </div>
          </div>
        </div>
      </div>
</body>
</html>
  