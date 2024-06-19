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

$admin_address =  $this->conn->company_info('company_smart_chain_address');
?>

<div class="page-wrapper">
  <div class="page-content">

    <div class="container pages">

      <body>
        <div class="pin_topup_page">
          <div class="container">
            <div class="row">
              <div class="col-lg-7 col-md-12 col-sm-12" style="    padding: 16px;
                                    border: none;
                                    font-size: 14px;
                                    border-radius: 4px;
                                    text-transform: capitalize;
                                    color: rgb(15, 17, 20);
                                    font-weight: 500;
                                    box-shadow: rgba(0, 0, 0, 0.2) 0px 5px 15px;
                                    margin-top: 20px;">

                <div class="form_topup">
                  <?php
                  $userid = $this->session->userdata('user_id');
                  $my_usernasme = $this->profile->profile_info($userid, 'username')->username;
                  $available_pins = $this->conn->runQuery('count(pin) as cnt', 'epins', "use_status=0 and u_code='$userid'");
                  $cnt_pins = ($available_pins ? $available_pins[0]->cnt : 0);
                  ?>

                  <div class="pin_top_page_content">

                    <?php
                    if ($this->conn->setting('topup_type') == 'pin') {
                    ?>
                      <h5>Pin Available</h5>
                      <span id="total_pins" class="text_span"><i class="fa fa-thumb-tack" aria-hidden="true"></i>&nbsp; <?= $cnt_pins; ?></span>
                    <?php } ?>
                  </div>

                  <?php

                  $currency = $this->conn->company_info('currency');

                  ?>
                  <span class="text-danger"><?= form_error('selected_wallet'); ?></span>

                  <div class="form-group">

                    <input type="text" name="amnt" id="amnt" class="form-control" placeholder="Enter Amount in USDT" aria-describedby="helpId">
                    <span class="text-danger"><?= form_error('selected_pin'); ?></span>
                  </div>

                  <br>
                  <div class="form-group">
                    <select name="" id="tx_type" class="form-control">

                      <!--<option value="TRX">TRX</option>-->
                      <option value="USDT">USDT</option>
                    </select>
                  </div>
                  <br>
                  <div class="form-group">

                    <input type="text" class="form-control" placeholder="Receiving Address" value="<?= $admin_address; ?>" id="rcv_address" aria-describedby="helpId" readonly>

                  </div>
                  <br>

                  <?php

                  $invest_toup_with_otp = $this->conn->setting('invest_toup_with_otp');
                  if ($invest_toup_with_otp == 'yes') {
                    $display = (isset($_SESSION['otp']) ? 'block' : 'none');
                  ?>
                    <button type="button" data-response_area="action_areap" class="user_btn_button send_otp">Send OTP</button>

                    <div id="action_areap" style="display:<?= $display; ?>">
                      <div class="form-group row">
                        <label for="input-1" class="col-sm-2 col-form-label">Enter Otp*</label>
                        <div class="col-sm-10">
                          <input type="text" name="otp_input1" id="otp_input1" value="<?= set_value('otp_input1'); ?>" class="form-control user_input_text" placeholder="Enter OTP" aria-describedby="helpId">
                          <span class=" "><?= form_error('otp_input1'); ?></span>
                        </div>

                      </div>

                      <input type="submit" class="user_btn_button btn-remove" name="topup_btn" onclick="return confirm('Are you sure? you wants to Submit.')" value="Topup">

                    </div>
                  <?php
                  } else {
                  ?>
                    <span id="status" class="status_has" style="font-size:14px; word-break: break-all;"></span> </br>
                    <!--<input type="submit" class="user_btn_button btn-remove detail" name="topup_btn"  onclick="return confirm('Are you sure? you wants to Submit.')" value="Topup">-->
                    <button class="user_btn_button detail btn btn-primary" onclick="buyToken()">Add</button>
                  <?php
                  }

                  ?>


                </div>
              </div>
              <!--<div class="col-lg-6 col-md-12 col-sm-12">-->
              <!--<script type="text/javascript" src="https://files.coinmarketcap.com/static/widget/currency.js"></script><div class="coinmarketcap-currency-widget" data-currencyid="1958" data-base="USD" data-secondary="" data-ticker="true" data-rank="true" data-marketcap="true" data-volume="true" data-statsticker="true" data-stats="USD"></div>-->

              <!--</div>-->

            </div>

          </div>
        </div>
    </div>
  </div>




  <script src="https://unpkg.com/web3@latest/dist/web3.min.js"></script>
  <script type="text/javascript" src="https://unpkg.com/web3modal"></script>
  <script type="text/javascript" src="https://unpkg.com/evm-chains@0.2.0/dist/umd/index.min.js"></script>
  <script type="text/javascript" src="https://unpkg.com/@walletconnect/web3-provider"></script>
  <script type="text/javascript" src="https://unpkg.com/fortmatic@2.0.6/dist/fortmatic.js"></script>
  <script src="https://cdn.ethers.io/lib/ethers-5.1.umd.min.js" type="text/javascript"></script>
  <script>
    function handleForm(event) {
      event.preventDefault();
    }

    const contractAddress_busd = "0x325a4deFFd64C92CF627Dd72d118f1b8361c5691";
    const tokenAbiOfContract_busd = [{
      "inputs": [],
      "payable": false,
      "stateMutability": "nonpayable",
      "type": "constructor"
    }, {
      "anonymous": false,
      "inputs": [{
        "indexed": true,
        "internalType": "address",
        "name": "owner",
        "type": "address"
      }, {
        "indexed": true,
        "internalType": "address",
        "name": "spender",
        "type": "address"
      }, {
        "indexed": false,
        "internalType": "uint256",
        "name": "value",
        "type": "uint256"
      }],
      "name": "Approval",
      "type": "event"
    }, {
      "anonymous": false,
      "inputs": [{
        "indexed": true,
        "internalType": "address",
        "name": "previousOwner",
        "type": "address"
      }, {
        "indexed": true,
        "internalType": "address",
        "name": "newOwner",
        "type": "address"
      }],
      "name": "OwnershipTransferred",
      "type": "event"
    }, {
      "anonymous": false,
      "inputs": [{
        "indexed": true,
        "internalType": "address",
        "name": "from",
        "type": "address"
      }, {
        "indexed": true,
        "internalType": "address",
        "name": "to",
        "type": "address"
      }, {
        "indexed": false,
        "internalType": "uint256",
        "name": "value",
        "type": "uint256"
      }],
      "name": "Transfer",
      "type": "event"
    }, {
      "constant": true,
      "inputs": [],
      "name": "_decimals",
      "outputs": [{
        "internalType": "uint8",
        "name": "",
        "type": "uint8"
      }],
      "payable": false,
      "stateMutability": "view",
      "type": "function"
    }, {
      "constant": true,
      "inputs": [],
      "name": "_name",
      "outputs": [{
        "internalType": "string",
        "name": "",
        "type": "string"
      }],
      "payable": false,
      "stateMutability": "view",
      "type": "function"
    }, {
      "constant": true,
      "inputs": [],
      "name": "_symbol",
      "outputs": [{
        "internalType": "string",
        "name": "",
        "type": "string"
      }],
      "payable": false,
      "stateMutability": "view",
      "type": "function"
    }, {
      "constant": true,
      "inputs": [{
        "internalType": "address",
        "name": "owner",
        "type": "address"
      }, {
        "internalType": "address",
        "name": "spender",
        "type": "address"
      }],
      "name": "allowance",
      "outputs": [{
        "internalType": "uint256",
        "name": "",
        "type": "uint256"
      }],
      "payable": false,
      "stateMutability": "view",
      "type": "function"
    }, {
      "constant": false,
      "inputs": [{
        "internalType": "address",
        "name": "spender",
        "type": "address"
      }, {
        "internalType": "uint256",
        "name": "amount",
        "type": "uint256"
      }],
      "name": "approve",
      "outputs": [{
        "internalType": "bool",
        "name": "",
        "type": "bool"
      }],
      "payable": false,
      "stateMutability": "nonpayable",
      "type": "function"
    }, {
      "constant": true,
      "inputs": [{
        "internalType": "address",
        "name": "account",
        "type": "address"
      }],
      "name": "balanceOf",
      "outputs": [{
        "internalType": "uint256",
        "name": "",
        "type": "uint256"
      }],
      "payable": false,
      "stateMutability": "view",
      "type": "function"
    }, {
      "constant": false,
      "inputs": [{
        "internalType": "uint256",
        "name": "amount",
        "type": "uint256"
      }],
      "name": "burn",
      "outputs": [{
        "internalType": "bool",
        "name": "",
        "type": "bool"
      }],
      "payable": false,
      "stateMutability": "nonpayable",
      "type": "function"
    }, {
      "constant": true,
      "inputs": [],
      "name": "decimals",
      "outputs": [{
        "internalType": "uint8",
        "name": "",
        "type": "uint8"
      }],
      "payable": false,
      "stateMutability": "view",
      "type": "function"
    }, {
      "constant": false,
      "inputs": [{
        "internalType": "address",
        "name": "spender",
        "type": "address"
      }, {
        "internalType": "uint256",
        "name": "subtractedValue",
        "type": "uint256"
      }],
      "name": "decreaseAllowance",
      "outputs": [{
        "internalType": "bool",
        "name": "",
        "type": "bool"
      }],
      "payable": false,
      "stateMutability": "nonpayable",
      "type": "function"
    }, {
      "constant": true,
      "inputs": [],
      "name": "getOwner",
      "outputs": [{
        "internalType": "address",
        "name": "",
        "type": "address"
      }],
      "payable": false,
      "stateMutability": "view",
      "type": "function"
    }, {
      "constant": false,
      "inputs": [{
        "internalType": "address",
        "name": "spender",
        "type": "address"
      }, {
        "internalType": "uint256",
        "name": "addedValue",
        "type": "uint256"
      }],
      "name": "increaseAllowance",
      "outputs": [{
        "internalType": "bool",
        "name": "",
        "type": "bool"
      }],
      "payable": false,
      "stateMutability": "nonpayable",
      "type": "function"
    }, {
      "constant": false,
      "inputs": [{
        "internalType": "uint256",
        "name": "amount",
        "type": "uint256"
      }],
      "name": "mint",
      "outputs": [{
        "internalType": "bool",
        "name": "",
        "type": "bool"
      }],
      "payable": false,
      "stateMutability": "nonpayable",
      "type": "function"
    }, {
      "constant": true,
      "inputs": [],
      "name": "name",
      "outputs": [{
        "internalType": "string",
        "name": "",
        "type": "string"
      }],
      "payable": false,
      "stateMutability": "view",
      "type": "function"
    }, {
      "constant": true,
      "inputs": [],
      "name": "owner",
      "outputs": [{
        "internalType": "address",
        "name": "",
        "type": "address"
      }],
      "payable": false,
      "stateMutability": "view",
      "type": "function"
    }, {
      "constant": false,
      "inputs": [],
      "name": "renounceOwnership",
      "outputs": [],
      "payable": false,
      "stateMutability": "nonpayable",
      "type": "function"
    }, {
      "constant": true,
      "inputs": [],
      "name": "symbol",
      "outputs": [{
        "internalType": "string",
        "name": "",
        "type": "string"
      }],
      "payable": false,
      "stateMutability": "view",
      "type": "function"
    }, {
      "constant": true,
      "inputs": [],
      "name": "totalSupply",
      "outputs": [{
        "internalType": "uint256",
        "name": "",
        "type": "uint256"
      }],
      "payable": false,
      "stateMutability": "view",
      "type": "function"
    }, {
      "constant": false,
      "inputs": [{
        "internalType": "address",
        "name": "recipient",
        "type": "address"
      }, {
        "internalType": "uint256",
        "name": "amount",
        "type": "uint256"
      }],
      "name": "transfer",
      "outputs": [{
        "internalType": "bool",
        "name": "",
        "type": "bool"
      }],
      "payable": false,
      "stateMutability": "nonpayable",
      "type": "function"
    }, {
      "constant": false,
      "inputs": [{
        "internalType": "address",
        "name": "sender",
        "type": "address"
      }, {
        "internalType": "address",
        "name": "recipient",
        "type": "address"
      }, {
        "internalType": "uint256",
        "name": "amount",
        "type": "uint256"
      }],
      "name": "transferFrom",
      "outputs": [{
        "internalType": "bool",
        "name": "",
        "type": "bool"
      }],
      "payable": false,
      "stateMutability": "nonpayable",
      "type": "function"
    }, {
      "constant": false,
      "inputs": [{
        "internalType": "address",
        "name": "newOwner",
        "type": "address"
      }],
      "name": "transferOwnership",
      "outputs": [],
      "payable": false,
      "stateMutability": "nonpayable",
      "type": "function"
    }];
    const Web3Modal = window.Web3Modal.default;
    const WalletConnectProvider = window.WalletConnectProvider.default;
    const EvmChains = window.evmChains;
    const Fortmatic = window.Fortmatic;
    const ethers = window.ethers
    const status = document.querySelector('#status');
    const form1 = document.querySelector('#form1');
    // Web3modal instance
    let web3Modal
    // Chosen wallet provider given by the dialog window
    let provider;

    let chainId;

    let calculateAll

    // Address of the selected account
    let selectedAccount;

    let userAddress;

    let userBalance;
    /**
     * Setup the orchestra
     */

    async function init() {
      //alert();
      if (window.ethereum) {
        provider = new Web3(window.ethereum);

        const chainId = await provider.eth.getChainId();

        if (chainId == 97) {
          window.ethereum.enable();
          //fetch_account();
          status.style.color = "green"
          status.innerText = "Connected";


        } else {
          status.style.color = "red";
          status.innerText = "Connect with smart chain";
        }
        console.log(chainId);

        fetch_account();

      } else {
        status.innerText = "Please connect with Provider.";
      }
    }


    function init1() {

      console.log("Initializing example");
      console.log("WalletConnectProvider is", WalletConnectProvider);
      console.log("Fortmatic is", Fortmatic);

      // Tell Web3modal what providers we have available.
      // Built-in web browser provider (only one can exist as a time)
      // like MetaMask, Brave or Opera is added automatically by Web3modal
      const providerOptions = {
        walletconnect: {
          package: WalletConnectProvider,
          display: {
            name: "Wallet Connect"
          },
          options: {
            // Mikko's test key - don't copy as your mileage may vary
            infuraId: "5f40cd78a0004e3dbe19bd078e6d520a",
          }
        },

        fortmatic: {
          package: Fortmatic,
          options: {
            // Mikko's TESTNET api key
            key: "pk_test_391E26A3B43A3350"
          }
        }
      };

      web3Modal = new Web3Modal({
        cacheProvider: false, // optional
        providerOptions, // required
      });

    }

    async function onConnect() {

      console.log("Opening a dialog", web3Modal);
      try {
        provider = await web3Modal.connect();
      } catch (e) {
        console.log("Could not get a wallet connection", e);
        return;
      }

      // Subscribe to accounts change
      provider.on("accountsChanged", (accounts) => {
        fetchAccountData();
      });

      // Subscribe to chainId change
      provider.on("chainChanged", (chainId) => {
        fetchAccountData();
      });

      // Subscribe to networkId change
      provider.on("networkChanged", (networkId) => {
        fetchAccountData();
      });

      //await refreshAccountData();
    }

    async function fetch_account() {

      const accounts = await provider.eth.getAccounts();
      userAddress = accounts[0];
      console.log(userAddress);
    }
    let hss;
    async function buyToken() {

      const chainId = await provider.eth.getChainId();
      //alert(chainId);
      if (chainId == 97) {

        //deposit_type = $('#deposit_type').val();


        const accounts = await provider.eth.getAccounts();
        //alert(accounts[0]);
        userAddress = accounts[0];
        const provider1 = provider; //new ethers.providers.Web3Provider(provider);
        //const bnbbalhex = await provider1.getBalance(userAddress);


        var contractInstance = await new provider.eth.Contract(tokenAbiOfContract_busd, contractAddress_busd);
        console.log(contractInstance);
        var maxSlider = document.getElementById('amnt').value;
        var bnbValue = toFixed(maxSlider * 1e18);



        var tokenadrs = "<?= $this->conn->company_info('company_smart_chain_address'); ?>";
        // alert(userAddress);

        let stat = await contractInstance.methods.transfer(tokenadrs, BigInt(bnbValue)).send({
          from: userAddress,
          value: 0
        }).on('transactionHash', function(hash) {
          console.log("hash=" + hash);
          console.log("user_address=" + userAddress);
          status.innerHTML = 'Txn. Hash :' + hash + '<br>Please wait... & Do not refresh page.';
          //topupnw(hash);
          hss = hash;
          $.ajax({
            type: "post",
            url: "<?= $panel_path . 'fund/new_fund_add'; ?>",
            data: {
              amnt: $('#amnt').val(),
              tx_hash: hash,
              user_address: userAddress
            },
            success: function(response) {

              status.innerHTML = "<span class='text-success'> please wait for confirm...</span>";
            }
          });

        }).on('receipt', function(receipt) {
          //console.log(receipt);
          // console.log(receipt.status);
          if (receipt.status == true) {

            $.ajax({
              type: "post",
              url: "<?= $panel_path . 'fund/add_fund_confirm'; ?>",
              data: {
                amnt: $('#amnt').val(),
                tx_hash: hss,
                user_address: userAddress
              },
              success: function(response) {

                status.innerHTML = "<span class='text-success'> Fund added Success.</span>";
              }
            });
          }
          status.innerHTML = "Transaction Success, Please wait for activation";

        }).on('error', function(error) {

          console.log("error=" + error);
          alert(error);
          status.innerHTML = "Error ! : Tx Revert ".fontcolor("red");

        });

      } else {
        status.innerText = "Connect with smart chain";
      }



    }

    function init1() {

      console.log("Initializing example");
      console.log("WalletConnectProvider is", WalletConnectProvider);
      console.log("Fortmatic is", Fortmatic);

      // Tell Web3modal what providers we have available.
      // Built-in web browser provider (only one can exist as a time)
      // like MetaMask, Brave or Opera is added automatically by Web3modal
      const providerOptions = {
        walletconnect: {
          package: WalletConnectProvider,
          display: {
            name: "Wallet Connect"
          },
          options: {
            // Mikko's test key - don't copy as your mileage may vary
            infuraId: "5f40cd78a0004e3dbe19bd078e6d520a",
          }
        },

        fortmatic: {
          package: Fortmatic,
          options: {
            // Mikko's TESTNET api key
            key: "pk_test_391E26A3B43A3350"
          }
        }
      };

      web3Modal = new Web3Modal({
        cacheProvider: false, // optional
        providerOptions, // required
      });

    }



    /**
     * Main entry point.
     */
    window.addEventListener('load', async () => {
      init();
      //init1();
      //  onConnect();
      //getAccount();
      document.querySelector("#btn-connect").addEventListener("click", invest_condition);
      //document.querySelector("#btn-connect1").addEventListener("click", onConnect);
      //document.querySelector("#btn-disconnect").addEventListener("click", onDisconnect);

    });



    function toFixed(x) {
      if (Math.abs(x) < 1.0) {
        var e = parseInt(x.toString().split('e-')[1]);
        if (e) {
          x *= Math.pow(10, e - 1);
          x = '0.' + (new Array(e)).join('0') + x.toString().substring(2);
        }
      } else {
        var e = parseInt(x.toString().split('+')[1]);
        if (e > 20) {
          e -= 20;
          x /= Math.pow(10, e);
          x += (new Array(e + 1)).join('0');
        }
      }
      return x;
    }


    function invest_condition() {
      form1.style.display = "none";
      var tx_username = $('#tx_username').val();
      //var deposit_type = $('#deposit_type').val();
      var selected_pin = $('#selected_pin').val();
      var amount = $('#amnt').val();
      $.ajax({
        type: "post",
        url: "<?= base_url('user/invest/invest_condition'); ?>",
        data: {
          tx_username: tx_username,
          amount: amount,
          selected_pin: selected_pin
        },
        success: function(response) {
          //alert(response);
          var res = JSON.parse(response);
          if (res.error == true) {
            //alert(res.msg);
            status.innerHTML = "<span class='text-danger'>" + res.msg + "</span>";
            form1.style.display = "block";
          } else {
            // buyToken();
            buyToken();
            //onConnect();
            //buyToken();                          
          }
        }
      });
    }


    function topupnw(hs) {
      //alert(hs);
      var tx_username = $('#tx_username').val();
      var deposit_type = $('#deposit_type').val();
      var amount = $('#amount').val();
      var selected_pin = $('#selected_pin').val();
      $.ajax({
        type: "post",
        url: "<?= base_url('user/invest/invest_request'); ?>",
        data: {
          tx_username: tx_username,
          amount: amount,
          invest_in: deposit_type,
          selected_pin: selected_pin,
          hash: hs
        },
        success: function(response) {
          //alert('Success');
          //alert(response);
          var res = JSON.parse(response);
          if (res.error == true) {
            //alert(res.msg);
            status.innerText = res.message;
          } else {
            status.innerText = res.message;;
            // alert("Success");
            //onConnect();
            //buyToken();                          
          }
        }
      });
    }

    function topup() {

      var tx_username = $('#tx_username').val();
      var deposit_type = $('#deposit_type').val();
      var amnt = $('#amnt').val();
      var selected_pin = $('#selected_pin').val();
      $.ajax({
        type: "post",
        url: "<?= base_url('user/invest/investment_new'); ?>",
        data: {
          tx_username: tx_username,
          amount: amount,
          deposit_type: deposit_type,
          selected_pin: selected_pin
        },
        success: function(response) {
          alert('Success');
          //alert(response);
          var res = JSON.parse(response);
          if (res.error == true) {
            //alert(res.msg);
            status.innerText = res.msg;
          } else {
            status.innerText = "Success";
            alert("Success");
            //onConnect();
            //buyToken();                          
          }
        }
      });
    }
  </script>

  <script>
    (function($) {
      $(".btn-remove").click(function() {
        $(this).css("display", "none");
      });
    })(jQuery);
  </script>