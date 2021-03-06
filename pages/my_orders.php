<?php
    include("./session_start.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders - Designs by Tabitha</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/css/iziToast.min.css"
        integrity="sha512-O03ntXoVqaGUTAeAmvQ2YSzkCvclZEcPQu1eqloPaHfJ5RuNGiS4l+3duaidD801P50J28EHyonCV06CUlTSag=="
        crossorigin="anonymous" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        #container {
            height: 100vh;
            display: flex;
            justify-content: center;
            background-image: url(../images/vector/header.svg);
            background-size: cover;
        }
        #content {
            background: white;
            box-shadow: 0 0 10px;
            padding: 20px;
        }
        .order-item {
            padding: 10px;
        }
        .orderBtns {
            text-align: center;
            margin: 5px;
            padding: 3px;
        }
        .orderBtns button{padding: 5px;}
    </style>
</head>
<body>
  <div id="container">
    <div id="content">
      <div style="margin: 10px">
        <button style="width: 100%" onclick="location.href = './session.php';">..Back</button>
      </div>
      <h1>Designs by Tabitha</h1>
      <h3>My Orders</h3>
      <div id="orderList"></div>
      <div id="orderDetails"></div>
    </div>
  </div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/js/iziToast.min.js"
    integrity="sha512-Zq9o+E00xhhR/7vJ49mxFNJ0KQw1E1TMWkPTxrWcnpfEFDEXgUiwJHIKit93EW/XxE31HSI5GEOW06G6BF1AtA=="
    crossorigin="anonymous"></script>
<script>

    function fetchMyOrders(){
      return new Promise(resolve =>{
        fetch('../php/get_my_orders.php', {
          method: 'GET',
          headers: {
            'Content-Type': 'application/json',
          },
        })
        .then(response => response.json())
        .then(data => {
          resolve(data);
        })
        .catch((error) => {
          console.error('Error:', error);
        });
      })
    }

    function fetchOrderDetails(orderNum){
      return new Promise(resolve =>{
        fetch('../php/get_order_details.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify(orderNum),
        })
        .then(response => response.json())
        .then(data => {
          resolve(data)
        })
        .catch((error) => {
          console.error('Error:', error);
        });
      })
    };
    
    function fetchDeleteOrder(orderNum){
      return new Promise(resolve =>{
        fetch('../php/post_delete_order.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify(orderNum),
        })
        .then(response => response.json())
        .then(data => {
          resolve(data)
        })
        .catch((error) => {
          console.error('Error:', error);
        });
      })
    };

    async function fetchAndRenderOrders(){
      const result = await fetchMyOrders();
      let orderListDiv = document.getElementById("orderList")
      orderListDiv.innerHTML = "";

      result.forEach(function(value, index){
        orderListDiv.insertAdjacentHTML("beforeend", `
          <div class="order-item">
            <p>Order Number: ${value.orderNum}</p>
            <p>Date: ${value.Date}</p>
            <p>Status: ${value.orderStatus}</p>
            <div class="orderBtns">
              <button onclick="fetchAndRenderDetails(${value.orderNum})">Details</button>
              <button onclick="confirmDeleteOrder(${value.orderNum}).then(fetchAndRenderOrders())"><bold>Cancel</bold></button>
            </div>
          </div>
          <hr>
        `)
      })

      if(document.getElementById("orderList").children.length === 0){
        document.getElementById("orderList").insertAdjacentHTML("beforeend", `
          <h1>No orders found!</h1>
        `)
      }
            
    };
    fetchAndRenderOrders();

    async function fetchAndRenderDetails(orderNum){
      const result = await fetchOrderDetails(orderNum)

      let orderDetailsDiv = document.getElementById("orderDetails");
      orderDetailsDiv.innerHTML = "";
      orderDetailsDiv.insertAdjacentHTML("afterbegin", `
        <hr>
        <h3>Details for Order Number <strong>${orderNum}</strong></h3>
      `)

      result.forEach(function(value, index){
        document.getElementById("orderDetails").insertAdjacentHTML("beforeend", `
          <p>Item: ${value.item}</p>
          <p>Size: ${value.size}</p>
          <p>Quantity: ${value.qnty}</p>
          <hr>
        `)
      })
    }

    function confirmDeleteOrder(orderNum){
      iziToast.show({
        timeout: 0,
        theme: 'dark',
        icon: 'icon-person',
        title: 'Cancel Order?',
        position: 'center',
        overlay: true,
        buttons: [
          [`<button>Yes - Cancel order ${orderNum}</button>`, function (instance, toast) {
            deleteAndRenderOrders(orderNum);
            iziToast.destroy();
          }], // true to focus
          ['<button> NO </button>', function (instance, toast) {
            iziToast.destroy();
          }]
        ],
      });
    }
    
    async function deleteAndRenderOrders(orderNum){
      const result = await fetchDeleteOrder(orderNum);
      fetchAndRenderOrders();
      document.getElementById("orderDetails").innerHTML = "";
    }

</script>
</body>
</html>