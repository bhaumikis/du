<div id="sticky-contain">
<div class="right_contain">
     <?php
     // if( $option=="cart" && $action=="checkout" ) $display="block"; else $display = "none";
     if($option!="cart" && $action!="checkout" && $action!="upload" && $option != "faqs"){?>
         <!--summary area-->
         <div class="right_contain_area">
              <div class="summary_title summary_bg"><h1>Summary</h1></div>
              <div class="contain_txt contain_bg">
                   <div class="summary_setting summary_setting_imgbg" id="summary_bg">
                        <div id="summary_default">
                             <b style="font-size: 16px;font: 15px/16px sans-serif;">Get Started<br/></b>
                             <span style="font-family: Lucida Handwriting; font-weight: normal; width: 195px;">
                             Begin selecting a testing package.
                             <br/><br/>
                             Note: to learn more about testing packages choose "Learn more".
                             </span>
                        </div>
                        <div id="testing_type" <?php if(count($view->session_cart) == 0) echo 'style="display: none;"';?>></div>
                   </div>
                   <div  class="summary_setting">
                        <div style="margin-top: 5px;  display: none;" id="devices"></div>
<!--                        <a class="graybtn addtocart_link ptr" id="addtocart" value="Add To Cart" disabled="disabled">Add to Cart</a>-->
                        <a class="graybtn" id="addtocart" value="Add To Cart">Add to Cart</a>
                   </div>
               </div> 
<div class="clrboth"></div>
              </div>
    
                 
         <!--summary area-->
         <?php 
     }
     if( $option=="cart" && $action=="checkout" ) 
     {
          $display="block"; 
  
    ?>       <!--cart area-->
         <div class="right_contain_area" id="cart_section" style="<?php if(count($view->session_cart) == 0 || $action=="upload") echo "display: $display;";?> z-index: 120; position: relative;">
              <div class="summary_title cart_bg"><h1>Your Cart</h1></div>
              <div class="contain_txt contain_bg">
                   <div class="summary_setting">
                        <div id="cart_default" <?php if(count($view->session_cart) > 0) echo 'style="display: none;"';?>>
                             <b style="font-size: 16px;">Get Started<br/></b>
                             <span style="font-family: Lucida Handwriting; font-weight: normal; width: 195px;">
                             Select a testing package and devices you want to test on then add it to your cart!
                             <br/><br/>
                             Note: to learn more about testing packages choose "Learn more".
                             </span>
                        </div>
                        <div id="cart" <?php if(count($view->session_cart) == 0) echo 'style="display: none;"';?>></div>
                   </div>
                  <? php /* 
                  <div  class="summary_setting">
                             <a class="<?php echo $checkout_class;?> ptr example11" id="checkout">Checkout</a>
                   </div>
                   
                   */ ?>
              </div>
              <div class="clrboth"></div>
         </div>

    <?php
     }
    else $display = "none";?>
         <!--cart area-->
         
   
     
   
     <?php 
     if ($action=="confirmation")
          {?>
         <!--completion date-->
    <div class="right_contain_area">
              <div class="summary_title summary_bg"><h1 style="font: 15px/16px sans-serif; ">Completion Date</h1></div>
              <div class="contain_txt contain_bg">
                   
                        <div id="summary_default" style="color:#283035;">
                            <p> <b style="padding-left:9px;">Order Submitted</b><br>
                             <?php
                             $qry = mysql_query("select created_date from orders  ORDER BY order_id DESC 
                        LIMIT 1");
                             while($res = mysql_fetch_array($qry))
                             {
                                  $c_date =  $res["created_date"]; 
                                 echo "&nbsp;&nbsp;".$newDate = date("d F Y", strtotime($c_date));

                             }
                             ?></p>
                             <b style="padding-left:9px;float: left;">Estimated Date of<br> Completion</b><br><br>
                             <?php
                             $qry = mysql_query("select estimated_completion_date from orders  ORDER BY order_id DESC 
                        LIMIT 1");
                             while($res = mysql_fetch_array($qry))
                             {
                                  $c_date =  $res["estimated_completion_date"]; 
                                 echo "<div style='padding-top:8px;'>&nbsp;&nbsp;".$newDate = date("d F Y", strtotime($c_date))."</div>";

                             }
                             ?>
                          <?php   $pages = "completion_date";
               if(isset($_REQUEST['learn'])) {
                   $pages = "learn_more";
               }
               $content_res = generalFunctions::getContent($pages);
               echo $content_res[0]['fulltext'];
               ?>
                        </div>
               </div>
<div class="clrboth"></div>
              </div>
    <!--completeion date ends-->
         
         <div class="right_contain_area">
              <div class="summary_title summary_bg"><h1 style="font: 15px/16px sans-serif; ">FAQs</h1></div>
              <div class="contain_txt contain_bg">
                   
                        <div id="summary_default">
                          <?php   $pages = "faqs";
               if(isset($_REQUEST['learn'])) {
                   $pages = "learn_more";
               }
               $content_res = generalFunctions::getContent($pages);
               echo $content_res[0]['fulltext'];
               ?>
                        </div>
               </div>
                    <div class="clrboth"></div>
              </div>
         
               
 <?php }
     
     if( ($option!="cart" && $action!="checkout" && $action!="upload") || $action=="confirmation" ) { ?>
     <!--contact area-->
     <div class="contact_area right_contain_area">
              <?php $content_res = generalFunctions::getContent("contact_us"); echo $content_res[0]['fulltext'] ?>
              <div class="clrboth"></div>
         </div>
     <!--contact area-->
         <?php 
     }?>
</div>
</div>