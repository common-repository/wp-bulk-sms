<?php

namespace WBS_WP_SMS_TO_BULK;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

/**
 * Class Documentation
 * 
 * This plugin is a fork from https://wordpress.org/plugins/wp-sms/ developed by VeronaLabs
 * @author mostafa.s1990, kashani, mehrshaddarzi, alifallahrn, panicoschr10
 * @copyright  2020 VeronaLabs
 * @license    GPLv3
 * @license uri: http://www.gnu.org/licenses/gpl.html
 * 
 * 
 */
#[\AllowDynamicProperties]
class WBS_SMS_TO_Documentation {
    /*
     * Show MetaBox System
     */

    public function render_page() {
        echo'</br></br></br></br>';
        echo '<div class="wpsmstobulk_registration_table_layout wpsmstobulk-otp-center">';
        echo'	    <table style="width:100%">
	            <tr>
                    <td colspan="">
                        <h2>' . _("DOCUMENTATION") . '
                            <span style="float:right;margin-top:-10px;">
                                <span   class="dashicons dashicons-arrow-up toggle-div" 
                                        data-show="false" 
                                        data-toggle="wpsmstobulk_form_instructions">                                            
                                </span>
                            </span>
                        </h2> <hr>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div id="wpsmstobulk_form_instructions">
                            <div class="wpsmstobulk_otp_note">
                                <b><div class="wpsmstobulk_otp_dropdown_note" data-toggle="how_to_use_the_otp_plugin">
                                    ' . _('ENVIRONMENT') . '
                                    </div></b>
                                <div id="how_to_use_the_otp_plugin" hidden >

</br>
 <strong>     ' . _("GET WORDPRESS") . '</strong>:         
<ol>
    <li>' . _("Visit ") . '
        <i><a href="' . 'https://wordpress.org' . '">' . _("Wordpress") . '</a></i>
    </li>
    <li>' . '
        <i><a href="' . 'https://wordpress.org/download' . '">' . _(" Download ") . '</a></i>Wordpress
    </li>    


    <li>' . _("Copy the contents of the downloaded zip file under your web server");
        echo'									</li>
         
 </i>
 </ol>



  <strong>    ' . _("DB CONFIGURATION") . '</strong>:         
<ol>
    <li>' . _("Visit phpmyadmin");
        echo'									</li>
    <li>' . _("Add User account : wordpress(can be something else) to create the WordPress user to hold WP tables");
        echo'									</li>
    <li>' . _("Host name  : localhost");
        echo'									</li>            
    <li>' . _("Password : wordpress(can be something else)");
        echo'									</li>  
    <li>' . _("Create database with same name and grant all privileges - select it");
        echo'									</li>  
    <li>' . _("Grant all privileges on wildcard name (username_%) - select it");
        echo'									</li>  
    <li>' . _("Global privileges Check all - select it");
        echo'									</li>  
    <li>' . _("Select GO");
        echo'									</li>  
            
   </i>
   </ol>








  <strong>    ' . _("WORDPRESS INSTALLATION") . '</strong>:         
<ol>
    <li>' . _("Browse to the folder where you extracted Wordpress");
        echo'									</li>
    <li>' . _("Database Name : wordpress (as above)");
        echo'									</li>
    <li>' . _("Username : wordpress (as above)");
        echo'									</li>            
    <li>' . _("Password  : wordpress (as above)");
        echo'									</li>  
    <li>' . _("Database Host : localhost (as above)");
        echo'									</li>  
    <li>' . _("Table Prefix : wp_");
        echo'									</li>  
 </i>
 </ol>







  ' . _("If everything was used correctly you should see a message") . ':         
   </br>     </br> <strong> ' . _("All right, sparky! You’ve made it through this part of the installation. "
           . "WordPress can now communicate with your database. If you are ready, time now to…") . '</strong> 
<ol>
    <li>' . _("Run the installation");
        echo'									</li>
    <li>' . _("Provide a Site Title : my_wordpress_site");
        echo'									</li>
    <li>' . _("Username : wordpress (can be something else)");
        echo'									</li>            
    <li>' . _("Password : wordpress (can be something else - can change the proposed one)");
        echo'									</li>  
    <li>' . _("Your Email : wordpress@gmail.com (can be something else)");
        echo'									</li>  
    <li>' . _("Username and Password will be the site's administrator username and password");
        echo'									</li>  
             <li>' . _("Select Install WordPress.");
        echo'									</li>  
             <li>' . _("Once this is done, click Log In and use the site administrator username and password");
        echo'									</li>  
            
   </i>
  </ol>




 <strong>     ' . _("ACTIVE SMSTO ACCOUNT WITH SUFFICIENT FUNDS") . '</strong>:         
<ol>
                                        <li>' . '
                                            <i><a href="' . 'https://sms.to/register#/' . '">' . _("Sign Up ") . '</a></i>for an account </li>
                                        </i>
                                    </ol>

</div>
                             </div>
                            



  <div class="wpsmstobulk_otp_note">
                                <b><div class="wpsmstobulk_otp_dropdown_note" data-toggle="how_to_set_up_the_environment">
                                    ' . _('PLUGIN INSTALLATION') . '
                                    </div></b>
                                <div id="how_to_set_up_the_environment" hidden >
                           
   </br>                                 

 <strong>     ' . _("INSTALL WP - Bulk SMS - by SMS.to THROUGH WORDPRESS") . '</strong>:         
<ol>
                                        <li>' . _("Visit the plugins page within your dashboard and select ‘Add New’");
        echo'									</li>
    <li>' . _("Search for ‘WP - Bulk SMS - by SMS.to’");
        echo'									</li>
    <li>' . _("Activate WP - Bulk SMS - by SMS.to from your Plugins page");
        echo'									</li>
    <li>' . _("Refresh the page");
        echo'									</li>            
      </i>
   </ol>

 <strong>     ' . _("UNINSTALL WP - Bulk SMS - by SMS.to") . '</strong>:         
<ol>
                                        <li>' . _("De-activate WP - Bulk SMS - by SMS.to from your Plugins page");
        echo'									</li>
    <li>' . _("Remove .git folder which exists in the WP - Bulk SMS - by SMS.to plugin folder(if installed from github)");
        echo'									</li>
    <li>' . _("Delete Plugin");
        echo'									</li>
 
    </i>
   </ol>


</div>
                                

                            </div>





  <div class="wpsmstobulk_otp_note">
                                <b><div class="wpsmstobulk_otp_dropdown_note" data-toggle="how_to_set_up_basic_configuration">
                                    ' . _('BASIC CONFIGURATION') . '
                                    </div></b>
                                <div id="how_to_set_up_basic_configuration" hidden >
                           
   </br>                                 

 <strong>     ' . _("STEP A") . '</strong>:         
<ol>
    <li>' . '
        <i><a href="' . 'https://sms.to/login#/' . '">' . _("Sign In") . '</a></i> with your <i><a href="' . 'https://sms.to' . '">' . _("smsto") . '</a></i> account
    </li>
    
    <li>' . '
        <i><a href="' . 'https://sms.to/login#/add-funds' . '">' . _("Add funds") . '</a></i> to your account
    </li>


    <li>' . _("Go to API Key Authentication, enter a Title and generate an API Key");
        echo'									</li>
</br> 
      <img width="800" height="350" src="' . WBS_WP_SMS_TO_BULK_URL . 'assets/images/api_key.png' . '">' . '</img>
          </br> </br>
          
</br> 
      <img width="800" height="600" src="' . WBS_WP_SMS_TO_BULK_URL . 'assets/images/set_api_key.png' . '">' . '</img>
          </br> </br>

    <li>' . _("Copy the generated API Key into the WP - Bulk SMS - by SMS.to plugin - Settings - Gateway - API Key");
        echo'									</li>
    <li>' . _("Change Sender (optional)");
        echo'									</li>        
    <li>' . _("Save Changes");
        echo'									</li>              
     </i>
   </ol>


        



<strong>     ' . _("STEP B") . '</strong>:         
<ol>

    <li>' . _("Go to WP - Bulk SMS - by SMS.to plugin - Settings - Features - must be selected as below");
        echo'									</li>    

</br> 
      <img width="800" height="500" src="' . WBS_WP_SMS_TO_BULK_URL . 'assets/images/mobile-phone-settings.png' . '">' . '</img>
          </br> </br>            
    </i>
  </ol>

   
  




<strong>     ' . _("STEP C") . '</strong>:         
<ol>

    <li>' . _("Go to WordPress Users - Your Profile - Add the mobile phone - Update the Profile");
        echo'									</li>    

</br> 
      <img width="800" height="500" src="' . WBS_WP_SMS_TO_BULK_URL . 'assets/images/user-profile-mobile-phone.png' . '">' . '</img>
          </br> </br>        
          

    <li>' . _("Enable WP User registration so you can get the mobile phone during the time of registration");
        echo'									</li>  
    <li>' . _("Go to WordPress - Settings - General - Membership - select it.");
        echo'									</li>  
    <li>' . _("Save Changes");
        echo'									</li>              
            
</br> 
      <img width="800" height="500" src="' . WBS_WP_SMS_TO_BULK_URL . 'assets/images/allow-registration.png' . '">' . '</img>
          </br> </br>  
          
</br> 
      <img width="400" height="600" src="' . WBS_WP_SMS_TO_BULK_URL . 'assets/images/user-registration.png' . '">' . '</img>
          </br> </br>  
    </i>
 </ol>




<strong>     ' . _("STEP D") . '</strong>:         
<ol>

    <li>' . _("Enable receiving Updates on the SMS you sent - Go to WP - Bulk SMS - by SMS.to plugin - Settings - Gateway - Callback URL. "
                . "By default the system has set the callback URL to update Reports.  If you must change it, please do it with caution. "
                . "This is the URL of your domain together with WordPress path");
        echo'									</li>    
            
    <li>' . _("Save Changes");
        echo'									</li>  

</br> 
      <img width="800" height="600" src="' . WBS_WP_SMS_TO_BULK_URL . 'assets/images/callback-url-setting.png' . '">' . '</img>
          </br> </br>        

    </i>
 </ol>




</div>
  
                            </div>






  <div class="wpsmstobulk_otp_note">
                                <b><div class="wpsmstobulk_otp_dropdown_note" data-toggle="how_to_send_sms">
                                    ' . _('SEND SMS') . '
                                    </div></b>
                                <div id="how_to_send_sms" hidden >
                           
   </br>                                 

 <strong>     ' . _("To send an SMS go to WP - Bulk SMS - by SMS.to - Send SMS") . '</strong>:         
<ol>

</br> 
      <img width="800" height="600" src="' . WBS_WP_SMS_TO_BULK_URL . 'assets/images/send-sms-wp-users.png' . '">' . '</img>
          </br> </br>     
          
</br> 
      <img width="800" height="600" src="' . WBS_WP_SMS_TO_BULK_URL . 'assets/images/send-sms-role-wp.png' . '">' . '</img>
          </br> </br>  
          
          
</br> 
      <img width="800" height="600" src="' . WBS_WP_SMS_TO_BULK_URL . 'assets/images/send-sms-single-number.png' . '">' . '</img>
          </br> </br>  

          </br> 
      <img width="800" height="600" src="' . WBS_WP_SMS_TO_BULK_URL . 'assets/images/send-sms-numbers.png' . '">' . '</img>
          </br> </br> 


   ' . _("Send from: takes the value from Settings - Gateway - Sender") . '      </br></br>
       
   ' . _("Send to: Drop down list to select from ") . ':      </br> </br>
<ol>
    <li>' . _("WordPress Users - Selects all Wordpress users that have a mobile phone");
        echo'									</li>
    <li>' . _("Role - Select a role to send the SMS to (Can be any role of WordPress). Available roles are ");
        echo'		</br>	</br>				
 <ol>           
<li>Administrator</li>
<li>Editor</li>
<li>Author</li>
<li>Contributor</li>
<li>Subscriber</li>
</br>
</ol>

</li>       

    <li>' . _("Single Number - Enter mobile phone number to send to");
        echo'									</li>
            
    <li>' . _("Paste Number(s) - Enter mobile phone number(s) to send to. You can enter more than one mobile phone numbers separated by a comma");
        echo'									</li>


   </i>
 </ol>




' . _("Message : Message content has a limitation of 480 characters. "
                . "Each SMS can carry/send 160 characters - if the message content holds more than 160 characters then the content is split to 2 or more parts, with 153 characters maximum in each part.") . '      </br></br>
   ' . _("Send a flash : Flash sends messages which are opened without being asked if the Provider, Country and Network allow") . '      </br> </br>
   ' . _("SEND SMS : Press the button to SEND SMS") . ':      </br> </br>

          </br> 
      <img width="800" height="600" src="' . WBS_WP_SMS_TO_BULK_URL . 'assets/images/sms-sending-in-process-wp.png' . '">' . '</img>
          </br> </br> 

    </i>
  </ol>




</div>

                            </div>




  <div class="wpsmstobulk_otp_note">
                                <b><div class="wpsmstobulk_otp_dropdown_note" data-toggle="reports">
                                    ' . _('REPORTS') . '
                                    </div></b>
                                <div id="reports" hidden >
                           
 </br> 
  
<strong>     ' . _("MESSAGES : Show single messages") . '</strong>   
  </br>  </br>
 
          </br> 
      <img width="900" height="160" src="' . WBS_WP_SMS_TO_BULK_URL . 'assets/images/outbox.png' . '">' . '</img>
          </br> </br> 
          



<ol>

    <li>' . _("Date : Date and Time the message was sent");
        echo'									</li>
    <li>' . _("Message Id : The Id of the message given by SMS to, to your message. This is a unique Id and it can be used for any troubleshooting");
        echo'									</li>            
    <li>' . _("Sender : Sender of the message");
        echo'									</li>
    <li>' . _("Recipient : Recipient(s) of the SMS");
        echo'									</li>            
    
<li>' . _("Message : Content of the SMS");
        echo'									</li>
    <li>' . _("Cost : Cost of the SMS");        
        echo'									</li>             
<li>' . _("Status : Status of the SMS - Available Statuses are : ");
        echo'									</li>       
            </br>
  <ol>           
<li>FAILED, REJECTED, UNDELIVERED, EXPIRED, UNSUBSCRIBED</li>
<li>PENDING, ONGOING, SENDING, QUEUED</li>
<li>DELIVERED, SENT</li>
</br>
</ol>          

  </i>
</ol>

  </br> </br> 
<strong>     ' . _("CAMPAIGNS : Show Campaigns") . '</strong>     

 </br> </br> 

          </br> 
      <img width="900" height="160" src="' . WBS_WP_SMS_TO_BULK_URL . 'assets/images/campaign-reports.png' . '">' . '</img>
          </br> </br> 
          

          </br> 
      <img width="900" height="160" src="' . WBS_WP_SMS_TO_BULK_URL . 'assets/images/campaign-reports-sent.png' . '">' . '</img>
          </br> </br> 
 <ol>

    <li>' . _("Date : Date and Time the campaign was sent");
        echo'									</li>
    <li>' . _("Campaign Id : The Id of the campaign given by SMS to, to your campaign. This is a unique Id and it can be used for any troubleshooting");
        echo'									</li>            
    <li>' . _("Sender : Sender of the message");
        echo'									</li>
    <li>' . _("Type : SMS");
        echo'									</li>
    <li>' . _("Cost : Cost of the campaign");        
        echo'									</li>            
    <li>' . _("Recipient : Recipients of the campaign. The link will re-direct you to your campaign details on the to SMS.to site - You need to be logged in, to access campaign details");
        echo'									</li>            
    
<li>' . _("Total Messages : Number of messages included in the campaign - actually this is equivalent to the number of recipients");        
        echo'									</li>            
            
<li>' . _("Parts : Number of SMS your message has been split into, based on the number of characters of the message content. Each message can hold up to 160 characters, so for a message of 180 characters, this will split into 2 messages.");
        echo'									</li>
            
<li>' . _("Sent : The number of messages that have been sent to the recipient");
        echo'									</li>
<li>' . _("Failed : The number of message that have failed");
        echo'									</li>
<li>' . _("Sent : The number of message that are still pending");
        echo'									</li>
            
<li>' . _("Message : Content of the SMS");
        echo'									</li>
<li>' . _("Status : Status of the SMS - Available Statuses are : ");
        echo'									</li>       
            </br>
  <ol>           
<li>FAILED, REJECTED, UNDELIVERED, EXPIRED, UNSUBSCRIBED</li>
<li>PENDING, ONGOING, SENDING, QUEUED</li>
<li>DELIVERED, SENT</li>
</br>
</ol>                  



   ' . _("List can can be sorted by clicking on columns, and can be searched for a matching string. You can also select Records and Bulk Delete. To Delete - Select Records, Bulk Actions - Delete and Apply.");
        echo'									</li>

    </i>
 </ol>


</br> </br>



</br>

<strong>     ' . _("Status Descriptions") . '</strong>      
    
   </br> 
   </br>
   </br>
      <img width="800" height="500" src="' . WBS_WP_SMS_TO_BULK_URL . 'assets/images/statuses.png' . '">' . '</img>
          </br> </br> 


<strong>     ' . _("Note:         The only non-billable status are: REJECTED, UNSUBSCRIBED.") . '</strong>  
 </br>
 </br>
 
<li>' . _("SMS.to partners with different carriers and network providers. These providers charge per submitted SMS message. All submitted messages from SMS.to platform to the local network providers were charged regardless of the status.");
        echo'									</li>

 
<li>' . _("We have no interest or advantage if you get any FAILED messages, your loss is our loss and we ourselves get charged the same amount for FAILED and DELIVERED. If a phone number is recognized as invalid, we do not even submit it for delivery and the message will remain in status REJECTED without any charge for you.");
        echo'									</li>


<li>' . _("Anyhow, the possibility remains that a phone number has a valid format but is still inactive, without reception, etc. ");
        echo'									</li>


 </br>
 </br>

                  

</div>
                                
   </div>





  <div class="wpsmstobulk_otp_note">
                                <b><div class="wpsmstobulk_otp_dropdown_note" data-toggle="privacy">
                                    ' . _('PRIVACY') . '
                                    </div></b>
                                <div id="privacy" hidden >
                           
   </br>                                 
          </br> 
      <img width="800" height="300" src="' . WBS_WP_SMS_TO_BULK_URL . 'assets/images/privacy.png' . '">' . '</img>
          </br> </br> 


 <strong>     ' . _("Deals with Privacy issues Go to WP - Bulk SMS - by SMS.to - Privacy") . '</strong>:         
<ol>
    <li>' . _("According to Article 17 GDPR, the user (data subject) shall have the right to "
            . "obtain his/her data or have them erased and forgotten. In WP - Bulk SMS - by SMS.to plugin you "
            . "can export the user's data or erase his/her data in the case she/he asks. For more information, read ") . '
        <i><a href="' . 'https://gdpr-info.eu/art-17-gdpr/' . '">' . _("Article 17 GDPR") . '</a></i>
    </li>
    
    <li>' . _("To export a user data based on mobile phone, please enter the mobile phone in User’s Mobile Number field and select Export");
        echo'									</li>
            

          </br> 
      <img width="800" height="500" src="' . WBS_WP_SMS_TO_BULK_URL . 'assets/images/privacy-export.png' . '">' . '</img>
          </br> </br> 
          

    <li>' . _("To delete a user data based on mobile phone, please enter the mobile phone in Erase Users Data Mobile Number field and select Delete. This action can not be undone");
        echo'									</li>
            

          </br> 
      <img width="800" height="500" src="' . WBS_WP_SMS_TO_BULK_URL . 'assets/images/privacy-delete.png' . '">' . '</img>
          </br> </br> 
         
   </i>
 </ol>


</div>
                            </div>





  <div class="wpsmstobulk_otp_note">
                                <b><div class="wpsmstobulk_otp_dropdown_note" data-toggle="general_settings">
                                    ' . _('GENERAL SETTINGS') . '
                                    </div></b>
                                <div id="general_settings" hidden >
                           
   </br>                                 

 <strong>     ' . _("General") . '</strong>:         
<ol>
    
    <li>' . _("Operator mobile phone number :  Operator mobile phone number for getting sms notifications");
        echo'									</li>
            
    <li>' . _("Privacy - GDPR Enhancements :  Enable this to have access to the Privacy Menu that deals with User Personal Data");
        echo'									</li>

          </br> 
      <img width="800" height="350" src="' . WBS_WP_SMS_TO_BULK_URL . 'assets/images/settings-general.png' . '">' . '</img>
          </br> </br> 

         
    </i>
</ol>

</div>
  </div>





  <div class="wpsmstobulk_otp_note">
                                <b><div class="wpsmstobulk_otp_dropdown_note" data-toggle="gateway_settings">
                                    ' . _('GATEWAY SETTINGS') . '
                                    </div></b>
                                <div id="gateway_settings" hidden >
                           
   </br>     
 <strong>     ' . _("Gateway") . '</strong>:         
<ol>

          </br> 
      <img width="800" height="800" src="' . WBS_WP_SMS_TO_BULK_URL . 'assets/images/settings-gateway.png' . '">' . '</img>
          </br> </br> 
    
    <li>' . _("API Key : ") . '
        <i><a href="' . 'https://sms.to/app#/api/client' . '">' . _("Get the API Key") . '</a></i> and set it
    </li>

          

    <li>' . _("Callback URL : The callback url is set by the installation of the plugin. It deals with receiving updates from the SMS.to site for the "
            . "sms sent. i.e A callback sent from SMS to site will change "
            . "the status of a message from ONGOING to SENT. "
            . "If the Callback URL is not set, then no callbacks will be sent. If you need to change this, please do it with caution");
        echo'									</li>
            

    <li>' . _("Sender :  Sender for all SMS sent. The Sender field is set by the installation of the plugin to SMSto. "
            . "You can change this to any value. "
            . "The maximum length of the sender is up to 11 characters.");
        echo'									</li>


    <li>' . _("Gateway Status :  Shows information regarding the status of the gateway. Whether is active and supports Bulk SMS. "
            . "It also shows the rermaining balance");
        echo'									</li>

   </br> 

      <img width="800" height="800" src="' . WBS_WP_SMS_TO_BULK_URL . 'assets/images/settings-gateway-status.png' . '">' . '</img>
          </br>      </br>  
  

    <li>' . _("Account Balance:  Show in admin menu");
        echo'									</li>
    
   </br>   
   
      <img width="800" height="200" src="' . WBS_WP_SMS_TO_BULK_URL . 'assets/images/show-in-admin-menu.png' . '">' . '</img>
          </br> </br> 
            
<li>' . _("Account Balance:  Show in send SMS page");
        echo'									</li>
            
   </br> 
            
      <img width="800" height="600" src="' . WBS_WP_SMS_TO_BULK_URL . 'assets/images/show-in-send-sms-page.png' . '">' . '</img>
          </br> </br> 

    </i>
 </ol>


</div>
                            </div>
   

  <div class="wpsmstobulk_otp_note">
                                <b><div class="wpsmstobulk_otp_dropdown_note" data-toggle="features">
                                    ' . _('FEATURES') . '
                                    </div></b>
                                <div id="features" hidden >
                           
   </br>   
 <strong>     ' . _("Features") . '</strong>:         
<ol>
    
    <li>' . _("Add Mobile number field : Adds Mobile number field to user profile and register form");
        echo'									</li>
                 </br> 
      <img width="800" height="500" src="' . WBS_WP_SMS_TO_BULK_URL . 'assets/images/user-profile-mobile-phone.png' . '">' . '</img>
          </br> </br> 
          
      <img width="400" height="600" src="' . WBS_WP_SMS_TO_BULK_URL . 'assets/images/user-registration.png' . '">' . '</img>
          </br> </br> 


            
    <li>' . _("Enable for mobile phone fields : Adds country code in mobile phone field both for registration and User profile.");
        echo'									</li>
            


    <li>' . _("Only Countries : Select which countries you wish to have for selection. "
            . "Apart from the selected ones, no other countries mobile phone codes will be allowed. "
            . "Note : Leave the Only Countries field empty to allow all countries mobile phone codes. ");
        echo'									</li>
 
   </br> <img width="800"  height="400" src="' . WBS_WP_SMS_TO_BULK_URL . 'assets/images/only-countries.png' . '">' . '</img>
          </br> </br> 



    <li>' . _("Prefix Countries : Select which countries you wish to show also on the top of the selection, for quick access. "
            . "If you have made a country selection for Only Countries, then Prefix Countries must be part of this selection. "
            . "You can still set Prefix Countries even if you have not made a country selection for Only Countries. ");
        echo'									</li>


          </br> 
      <img width="800" height="400" src="' . WBS_WP_SMS_TO_BULK_URL . 'assets/images/prefix-countries.png' . '">' . '</img>
          </br> </br> 

    </i>
</ol>
                                    

</div>
       
 </div>


  <div class="wpsmstobulk_otp_note">
                                <b><div class="wpsmstobulk_otp_dropdown_note" data-toggle="notifications">
                                    ' . _('NOTIFICATIONS') . '
                                    </div></b>
                                <div id="notifications" hidden >
                           
   </br>
   
          </br> 
      <img width="900" height="1000" src="' . WBS_WP_SMS_TO_BULK_URL . 'assets/images/settings-notifications.png' . '">' . '</img>
          </br> </br>
 <strong>     ' . _("General Information") . '</strong>:         
     
<ol>
    
      ' . _("There are different notifications you can set for WordPress Events.  The common fields in all notifications are status and Message body.") . '   
   
 </br> </br> 
 
<li>' . _("Status : Set the status to active to enable the notification");
        echo'									</li>
            
    <li>' . _("Message body : The is the message that will be sent to the notification recipient. "
            . "Each notification message body has its own variables listed below. You can use them to create a permanent "
            . "template for each notification. You can simply copy the entire description text below message body, "
            . "into the message body, and Save. That will do the job. For instance, this is the New Comment Notification. "
            . "To enable it, tick the status and then copy the text below Message body into the Message body. Variable will have "
            . "their values and you will receive a complete SMS. Of, course you can add and change wording.");
        echo'									</li>


          </br> 
      <img width="800" height="400" src="' . WBS_WP_SMS_TO_BULK_URL . 'assets/images/message-body.png' . '">' . '</img>
          </br> </br> 

         
     </i>
 </ol>

<ol>
     <strong>  
      ' . _("Sends SMS to WordPress subscriber when publish new post.") . '    </strong>  
     </br> </br>       

    <li>' . _("Status : Set the status to active to enable the notification");
        echo'									</li>
            
    <li>' . _("Post content words count : Set this to a number to crop the number of words of the Post Content published. "
            . "Default number is 10. Below crop value is set to 4. So the Post Content in the SMS message is cropped to 4");
        echo'									</li>
            
      <img width="800" height="600" src="' . WBS_WP_SMS_TO_BULK_URL . 'assets/images/new-post.png' . '">' . '</img>
          </br> </br> 
          
      <img width="800" height="120" src="' . WBS_WP_SMS_TO_BULK_URL . 'assets/images/new-post-reports.png' . '">' . '</img>
          </br> </br> 


            
    <li>' . _("Message body : This is the message that will be sent to the notification recipient");
        echo'		
            
  </br> </br>  

          </br> 
      <img width="800" height="400" src="' . WBS_WP_SMS_TO_BULK_URL . 'assets/images/publish-new-post.png' . '">' . '</img>
          </br> </br> 

    </i>
 </ol>






<ol>
     <strong>  
      ' . _("Sends SMS to the author of the post when that post is published.") . '    </strong>  
          
   </br> </br> 
    <li>' . _("Status : Set the status to active to enable the notification");
        echo'									</li>
            
    <li>' . _("Post types : Select Post types from the drop down list. Must Select at least one to enable");
        echo'									</li>

    <li>' . _("Message body : This is the message that will be sent to the notification recipient");
        echo'		
            
  </br> 

          </br> 
      <img width="800" height="400" src="' . WBS_WP_SMS_TO_BULK_URL . 'assets/images/author-of-the-post.png' . '">' . '</img>
          </br> </br> 

         
    </i>
  </ol>





<ol>
     <strong>  
      ' . _("Sends SMS to Operator mobile phone number when a new release of WordPress") . '    </strong>  
          
   </br> </br> 
   
    <li>' . _("Status : Set the status to active to enable the notification");
        echo'									</li>
            
   
          </br> </br>  
      <img width="800" height="400" src="' . WBS_WP_SMS_TO_BULK_URL . 'assets/images/new-release.png' . '">' . '</img>
          </br> </br> 
   </i>
   </ol>




<ol>
     <strong>  
      ' . _("Sends SMS to Operator mobile phone number and to the user when registers on WordPress.") . '    </strong>  
          
   </br> </br> 
   
    <li>' . _("Status : Set the status to active to enable the notification");
        echo'									</li>
            

    <li>' . _("Operator Message body : This is the message that will be sent to the Operator notification recipient");
        echo'	</li>
   

    <li>' . _("User Message body : This is the message that will be sent to the User notification recipient");
        echo'	</li>
            
          </br> </br>  
      <img width="800" height="400" src="' . WBS_WP_SMS_TO_BULK_URL . 'assets/images/register-new-user.png' . '">' . '</img>
          </br> </br> 
   </i>
 </ol>






<ol>
     <strong>  
      ' . _("Sends SMS to Operator mobile phone number when get a new comment") . '    </strong>  
          
   </br> </br> 
   
    <li>' . _("Status : Set the status to active to enable the notification");
        echo'									</li>
            

    <li>' . _("Message body : This is the message that will be sent to the notification recipient");
        echo'	</li>
   
       </br> </br>  
      <img width="800" height="400" src="' . WBS_WP_SMS_TO_BULK_URL . 'assets/images/new-comment.png' . '">' . '</img>
          </br> </br> 
   </i>
 </ol>






<ol>
     <strong>  
      ' . _("Sends SMS to Operator mobile phone number when user is login") . '    </strong>  
          
   </br> </br> 
   
    <li>' . _("Status : Set the status to active to enable the notification");
        echo'									</li>
            

    <li>' . _("Message body : This is the message that will be sent to the notification recipient");
        echo'	</li>
   
        </br> </br>  
      <img width="800" height="400" src="' . WBS_WP_SMS_TO_BULK_URL . 'assets/images/user-login.png' . '">' . '</img>
          </br> </br> 
  </i>
 </ol>

</div>
                            </div>


                        </div>
                    </td>
                </tr>
            </table>
        </div>';
    }

}

new WBS_SMS_TO_Documentation();
