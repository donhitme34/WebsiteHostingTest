//add product data
function addItems(){
    var itemname=$('#itemname').val();
    var description=$('#description').val();
    var price=$('#price').val();
    var category=$('#category').val();
    var sellerID=$('#sellerID').val();
    var upload=$('#upload').val();
    var file=$('#file')[0].files[0];

    var fd = new FormData();
    fd.append('itemname', itemname);
    fd.append('description', description);
    fd.append('price', price);
    fd.append('sellerID', sellerID);
    fd.append('category', category);
    fd.append('file', file);
    fd.append('upload', upload);
    $.ajax({
        url:"./addItemController.php",
        method:"post",
        data:fd,
        processData: false,
        contentType: false,
        success: function(data){
            alert('Product Added successfully.');
            $('form').trigger('reset');
            document.location.reload();
        }
    });
}

//edit product data
function itemEditForm(id){
    $.ajax({
        url:"./editItemForm.php",
        method:"post",
        data:{record:id},
        success:function(data){
            $('.allContent-section').html(data);
        }
    });
}
//edit user data
function userEditForm(id){
    $.ajax({
        url:"./userEditForm.php",
        method:"post",
        data:{record:id},
        success:function(data){
            $('.allContent-section').html(data);
        }
    });
}

//update product after submit
function updateItems(){
    var idproduct = $('#idproduct').val();
    var itemname = $('#itemname').val();
    var description = $('#description').val();
    var price = $('#price').val();
    var sellerID = $('#sellerID').val();
    var category = $('#category').val();
    var existingImage = $('#existingImage').val();
    var newImage = $('#newImage')[0].files[0];
    var fd = new FormData();
    fd.append('idproduct', idproduct);
    fd.append('itemname', itemname);
    fd.append('description', description);
    fd.append('price', price);
    fd.append('sellerID', sellerID);
    fd.append('category', category);
    fd.append('existingImage', existingImage);
    fd.append('newImage', newImage);
   
    $.ajax({
      url:'./updateItemController.php',
      method:'post',
      data:fd,
      processData: false,
      contentType: false,
      success: function(data){
        alert('Data Update Success.');
        $('form').trigger('reset');
        document.location.reload();
      }
    });
}
//update user
function updateUsers(){
    var idUser = $('#idUser').val();
    var Username = $('#Username').val();
    var firstname = $('#firstname').val();
    var lastname = $('#lastname').val();
    var emailaddress = $('#emailaddress').val();
    var homeAddress = $('#homeAddress').val();
  
    var fd = new FormData();
    fd.append('idUser', idUser);
    fd.append('Username', Username);
    fd.append('firstname', firstname);
    fd.append('lastname', lastname);
    fd.append('emailaddress', emailaddress);
    fd.append('homeAddress', homeAddress);
   
    $.ajax({
      url:'./updateUserController.php',
      method:'post',
      data:fd,
      processData: false,
      contentType: false,
      success: function(data){
        alert('Data Update Success.');
        $('form').trigger('reset');
        document.location.reload();
      }
    });
}

//delete user
function userDelete(id){
    $.ajax({
        url:"./deleteUserController.php",
        method:"post",
        data:{record:id},
        success:function(data){
            alert('Items Successfully deleted');
            $('form').trigger('reset');
            document.location.reload();
        }
    });
}
//delete product data
function itemDelete(id){
    $.ajax({
        url:"./deleteItemController.php",
        method:"post",
        data:{record:id},
        success:function(data){
            alert('Items Successfully deleted');
            $('form').trigger('reset');
            document.location.reload();
        }
    });
}

//delete category data
function categoryDelete(id){
    $.ajax({
        url:"./catDeleteController.php",
        method:"post",
        data:{record:id},
        success:function(data){
            alert('Category Successfully deleted');
            $('form').trigger('reset');
            document.location.reload();
        }
    });
}
//change payment
//or release payment
function ChangePay(id){
    $.ajax({
       url:"./updatePayStatus.php",
       method:"post",
       data:{record:id},
       success:function(data){
           alert('Payment Status updated successfully');
           $('form').trigger('reset');
           document.location.reload();
       }
   });
}
//change order status
function ChangeOrderStatus(id){
    $.ajax({
       url:"./updateOrderStatus.php",
       method:"post",
       data:{record:id},
       success:function(data){
           alert('Order Status updated successfully');
           $('form').trigger('reset');
           document.location.reload();
       }
   });
}

//change release status
function ChangeReleaseStatus(id){
    $.ajax({
       url:"./updateReleaseStatus.php",
       method:"post",
       data:{record:id},
       success:function(data){
           alert('Release payment successfully');
           $('form').trigger('reset');
           document.location.reload();
       }
   });
}

function couponEditForm(id){
    $.ajax({
        url:"./couponEditForm.php",
        method:"post",
        data:{record:id},
        success:function(data){
            $('.allContent-section').html(data);
        }
    });
}

function updateCoupon(){
    var coupon_id = $('#coupon_id').val();
    var coupon_code = $('#coupon_code').val();
    var discount = $('#discount').val();
    var status = $('#status').val();
  
    var fd = new FormData();
    fd.append('coupon_id', coupon_id);
    fd.append('coupon_code', coupon_code);
    fd.append('discount', discount);
    fd.append('status', status);
   
    $.ajax({
      url:'./updateCouponController.php',
      method:'post',
      data:fd,
      processData: false,
      contentType: false,
      success: function(data){
        alert('Data Update Success.');
        $('form').trigger('reset');
        document.location.reload();
      }
    });
}

function couponDelete(id){
    $.ajax({
        url:"./deleteCouponController.php",
        method:"post",
        data:{record:id},
        success:function(data){
            alert('Category Successfully deleted');
            $('form').trigger('reset');
            document.location.reload();
        }
    });
}