<?php include('header.php');?>
<?php include('nav.php');?>
       
       	<div class="content">
            <div class="content">
            	<h1>Welcome Hamro Grocery</h1>

                

                <h3><u>WE'RE OPEN</u></h3>
                <h4>Everyday : 11:00 AM to 10:00 PM</h4>
                <h4>Address 56 Charlotte Street,</h4>
                <h4>Ashfield, NSW 2136, Australia</h4>
                <h4>Phone Number: +04 52571840</h4>
                <h4>Email: hamrogrocery@gmail.com</h4>
                <hr/>

                <script>
                    function confirm(){
                        var first_name = document.getElementById('first_name').value;
                        var last_name = document.getElementById('last_name').value;
                        var email = document.getElementById('email').value;
                        var phone_number = document.getElementById('phone_number').value;
                        var subject = document.getElementById('subject').value;
                        var message = document.getElementById('message').value;
                        
                        if(first_name !== null && first_name !== '' && last_name !== null && last_name !== ''&& email !== null && email !== '' && phone_number !== null && phone_number !== '' && subject !== null && subject !== '' && message !== null && message !== ''){                            
                        	alert("Thank you for contacting us.");
                        }
                        
                        
                    }
                </script>

                <h3>Enquiries and Feedbacks are highly appreciated!</h3>
                <form method="post" action="processing/customer-contact.php">
                    <table>
                        <tr>
                            <td>First Name: *</td>
                            <td><input type="text" name="first_name" id="first_name" required="required"></td>
                        </tr>
                        <tr>
                            <td>Last Name: *</td>
                            <td><input type="text" name="last_name" id="last_name" required="required"></td>
                        </tr>
                        <tr>
                            <td>Email: *</td>
                            <td><input type="email" name="email" id="email" required="required"></td>
                        </tr>
                        <tr>
                            <td>Phone Number: *</td>
                            <td><input type="text" name="phone_number" id="phone_number" required="required"></td>
                        </tr>
                        <tr>
                        	<td>Subject: *</td>
                        	<td>
                        		<select id="subject" name="subject">
                        			<option value="Enquiry">Enquiry</option>
                        			<option value="Feedback">Feedback</option>
                        			<option value="Others">Others</option>
                        	</td>
                        </tr>
                        <tr>
                        	<td>Message: *</td>
                        	<td><textarea id="message" name="message" rows="4" cols="50" required="required"></textarea></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td><input type="submit" value="SUBMIT" name="submit" id="submit" onclick="return confirm()"></td>
                        </tr>
                    </table>
                </form>
                
            
        		
        	</div>


        </div>

<?php include('footer.php');?>
       
        