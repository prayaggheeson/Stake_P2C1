<?php
error_reporting(0);
$profile = $this->session->userdata("profile");
$user_id = $this->session->userdata('user_id');
?>
<div class="page-wrapper">
   <div class="page-content">
      <div class="user_content">
         <div class="card">
            <div class="card-body">
               <div class="user_content">

                  <?php
                  $success['param'] = 'success';
                  $success['alert_class'] = 'alert-success';
                  $success['type'] = 'success';
                  $this->show->show_alert($success);
                  ?>
                  <?php
                  $erroralert['param'] = 'error';
                  $erroralert['alert_class'] = 'alert-danger';
                  $erroralert['type'] = 'error';
                  $this->show->show_alert($erroralert);
                  ?>


                  <style>
                     .main_Withdraw {
                        background-color: #fff;
                        border-radius: 10px;

                        padding: 16px 16px;
                        box-shadow: 0px 0px 5px 0px rgb(0 0 0 / 15%);
                        background-image: url(<?= base_url(); ?>/images/logo/widthraw.jpg);
                        background-repeat: no-repeat;
                        background-size: cover;
                     }

                     .main-balance h3 {
                        font-size: 20px;
                     }

                     .dolo_icon span {
                        color: #000 !important;
                     }
                  </style>
                  </head>

                  <body>
                     <div class="widthraw_page">
                        <div class="container">
                           <div class="row">
                              <div class="col-lg-7 col-md-12 col-sm-12">
                                 <div class="widthraw_page_style">
                                    <h4>Withdraw</h4>
                                    <div class="new_box_widthraw">
                                       <div class="main_Withdraw">
                                          <div class="main-balance">
                                             <div>
                                                <?php
                                                $total_paid_withdrawal = $this->conn->runQuery("SUM(amount) as amt", 'transaction', "u_code='$user_id' and tx_type='withdrawal' and status='1'")[0]->amt;
                                                ?>
                                                <h3>PAYOUT PAID AMOUNT</h3>
                                                <h1><?= $total_paid_withdrawal ? $total_paid_withdrawal : 0; ?></h1>
                                             </div>
                                             <div class="dolo_icon">
                                                <span><?= $this->conn->company_info('currency'); ?></span>
                                             </div>
                                          </div>
                                          <div class="row">
                                             <div class="col-lg-6 col-md-12 col-sm-12">
                                                <div class="main_box">
                                                   <h4>Minimum payout amount</h4>
                                                   <h6><?= $this->conn->company_info('currency'); ?>&nbsp;<?= $this->conn->setting('min_withdrawal_limit'); ?></h6>
                                                </div>
                                             </div>
                                             <div class="col-lg-6 col-md-12 col-sm-12">
                                                <div class="main_box">
                                                   <h4>Withdrawal Conditions</h4>
                                                   <h6><?= $this->conn->setting('wd_conditions'); ?></h6>
                                                </div>
                                             </div>
                                             <!--<div class="col-lg-6 col-md-12 col-sm-12">
                              <div class="main_box">
                                 <h4>Withdrawal Conditions</h4>
                                 <h6><?= $this->conn->setting('wd_conditions'); ?></h6>
                              </div>
                           </div>-->
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <?php
                              $days_allowed = $this->conn->setting('wd_days');
                              if ($days_allowed) {
                                 $daysjson_decode = json_decode($days_allowed);
                                 if (in_array(date('l'), $daysjson_decode)) {


                                    $wd_start_time = $this->conn->setting('wd_start_time');
                                    $str_time = date('H:i:s', strtotime($wd_start_time));
                                    $wd_end_time = $this->conn->setting('wd_end_time');
                                    $end_time = date('H:i:s', strtotime($wd_end_time));

                                    $nw_tm = date('H:i:s');

                                    if ($nw_tm >= $str_time && $nw_tm <= $end_time) {



                              ?>
                                       <div class="col-lg-5 col-md-12 col-sm-12">
                                          <div class="payout_request_widthraw">
                                             <h4>Payout Request</h4>
                                             <div class="payout_request_payout">
                                                <form action="" method="post">
                                                   <?php
                                                   $userid = $this->session->userdata('user_id');
                                                   $currency = $this->conn->company_info('currency');
                                                   $available_wallets = $this->conn->setting('withdrawal_wallets');

                                                   if ($available_wallets) {
                                                      $useable_wallet = json_decode($available_wallets);

                                                      if (count((array)$useable_wallet) > 1) {
                                                         foreach ($useable_wallet as $wlt_key => $wlt_name) {
                                                            $balance = round($this->update_ob->wallet($userid, $wlt_key), 2);
                                                            echo "$wlt_name : $currency $balance <br>";
                                                         }
                                                   ?>
                                                         <div class="form-group">
                                                            <label for="input-1" class="col-form-label">Select Wallet*</label>

                                                            <select name="selected_wallet" id="" class="form-control">
                                                               <option value="">Select Wallet</option>
                                                               <?php
                                                               foreach ($useable_wallet as $wlt_key => $wlt_name) {
                                                               ?>
                                                                  <option value="<?= $wlt_key; ?>"><?= $wlt_name; ?></option>
                                                               <?php
                                                               }
                                                               ?>
                                                            </select>

                                                         </div>
                                                         <?php
                                                      } else {
                                                         foreach ($useable_wallet as $wlt_key => $wlt_name) {
                                                            $balance = $this->update_ob->wallet($userid, $wlt_key);
                                                            echo "<span class=''>$wlt_name : $currency $balance</span>";
                                                         ?><input type="hidden" name="selected_wallet" value="<?= $wlt_key; ?>"><?php
                                                                                                                              }
                                                                                                                           }
                                                                                                                        }
                                                                                                                                 ?>
                                                   <div class="form_group detail">
                                                      <div class="input_data_widtr">
                                                         <span>PAYOUT AMOUNT *</span>
                                                      </div>
                                                      <div class="payout_rquest">
                                                         <input type="text" id="amount" name="amount" value="<?= set_value('amount'); ?>" class="form-control">
                                                         <span class="text-danger"><?= form_error('amount'); ?></span>
                                                      </div>
                                                   </div>

                                                   <?php
                                                   if ($profile_edited != 'readonly') {
                                                      $withdrawal_with_otp = $this->conn->setting('withdrawal_with_otp');
                                                      if ($withdrawal_with_otp == 'yes') {
                                                         $display = (isset($_SESSION['otp']) ? 'block' : 'none');
                                                   ?>
                                                         <button type="button" data-response_area="action_areap" class="submit_withrawal send_otp_withdrawal">Send OTP</button>
                                                         <div id="action_areap" style="display:<?= $display; ?>">
                                                            <div class="form-group">
                                                               <label for="">Enter OTP </label>
                                                               <input type="text" name="otp_input1" id="otp_input1" value="<?= set_value('otp_input1'); ?>" class="form-control user_input_text" placeholder="Enter OTP" aria-describedby="helpId">
                                                               <span class=" "><?= form_error('otp_input1'); ?></span>
                                                            </div>
                                                            <button type="submit" class="submit_withrawal btn-remove" name="withdrawal_btn" onclick="return confirm('Are you sure? you wants to Submit.')">submit</button>
                                                         </div>
                                                      <?php
                                                      } else {
                                                      ?>
                                                         <button type="submit" class="submit_withrawal btn-remove" name="withdrawal_btn" onclick="return confirm('Are you sure? you wants to Submit.')">submit</button>
                                                   <?php
                                                      }
                                                   }


                                                   ?>
                                                </form>
                                             </div>
                                          </div>
                                       </div>
                              <?php

                                    }
                                 }
                              }
                              ?>
                           </div>
                        </div>
                     </div>
                  </body>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<script>
   (function($) {
      $(".btn-remove").click(function() {
         $(this).css("display", "none");
      });
   })(jQuery);
</script>