<?php /* Smarty version 2.6.19, created on 2012-10-09 09:37:50
         compiled from text:%3C%21DOCTYPE+html+PUBLIC+%22-//W3C//DTD+XHTML+1.0+Transitional//EN%22+%22http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd%22%3E%0D%0A%3Chtml+xmlns%3D%22http://www.w3.org/1999/xhtml%22%3E%0D%0A%3Chead%3E%0D%0A%3Cmeta+http-equiv%3D%22Content-Type%22+content%3D%22text/html%3B+charset%3Dutf-8%22+/%3E%0D%0A%3Ctitle%3E%7Btrans%7DMAIL_TITLE%7B/trans%7D%3C/title%3E%0D%0A%09%3Cstyle+type%3D%22text/css%22%3E%0D%0A%09%7Bliteral%7D%0D%0A%09%09body%2Ctd%2Cth+%7B%0D%0A%09%09%09font-size:+11px%3B%0D%0A%09%09%09color:+%23575656%3B%0D%0A%09%09%09font-family:+Arial%3B%0D%0A%09%09%7D%0D%0A%09%09body+%7B%0D%0A%09%09%09margin-left:+0px%3B%0D%0A%09%09%09margin-top:+0px%3B%0D%0A%09%09%09margin-right:+0px%3B%0D%0A%09%09%09margin-bottom:+0px%3B%0D%0A%09%09%7D%0D%0A%09%09a:link+%7B%0D%0A%09%09%09color:+%23BF3131%3B%0D%0A%09%09%09text-decoration:+none%3B%0D%0A%09%09%7D%0D%0A%09%09a:visited+%7B%0D%0A%09%09%09text-decoration:+none%3B%0D%0A%09%09%09color:+%23BF3131%3B%0D%0A%09%09%7D%0D%0A%09%09a:hover+%7B%0D%0A%09%09%09text-decoration:+none%3B%0D%0A%09%09%09color:+%23BF3131%3B%0D%0A%09%09%7D%0D%0A%09%09a:active+%7B%0D%0A%09%09%09text-decoration:+none%3B%0D%0A%09%09%09color:+%23BF3131%3B%0D%0A%09%09%7D%0D%0A%09%7B/literal%7D%0D%0A%09%3C/style%3E%0D%0A%3C/head%3E%0D%0A%0D%0A%3Cbody%3E%0D%0A%3Ctable+width%3D%22100%25%22+border%3D%220%22+cellspacing%3D%220%22+cellpadding%3D%220%22%3E%0D%0A++%3Ctr%3E%0D%0A++++%3Ctd%3E%26nbsp%3B%3C/td%3E%0D%0A++++%3Ctd+width%3D%22500%22+align%3D%22left%22+valign%3D%22top%22%3E%3Ctable+width%3D%22100%25%22+border%3D%220%22+cellspacing%3D%220%22+cellpadding%3D%220%22%3E%0D%0A++++++%3Ctr%3E%0D%0A++++++++%3Ctd+height%3D%2296%22+align%3D%22left%22+valign%3D%22top%22%3E%3Ctable+width%3D%22100%25%22+border%3D%220%22+cellspacing%3D%220%22+cellpadding%3D%220%22%3E%0D%0A++++++++++%3Ctr%3E%0D%0A++++++++++%09%7Bif+isset%28%24FRONTEND_URL%29%7D%0D%0A++++++++++++%3Ctd+height%3D%2296%22+align%3D%22left%22+valign%3D%22middle%22%3E%3Cimg+src%3D%27cid:logo%27+alt%3D%22Logo+Sklep+Internetowy%22/%3E%3C/td%3E%0D%0A++++++++++++%3Ctd+width%3D%2260%22+align%3D%22left%22+valign%3D%22middle%22%3E%3Cfont+color%3D%22%23969696%22%3E%3Ca+href%3D%22%7B%24FRONTEND_URL%7D%7Bseo+controller%3Dmainside%7D%22+target%3D%22_blank%22%3E%7Btrans%7DTXT_MAINSIDE%7B/trans%7D%3C/a%3E%3C/font%3E%3C/td%3E%0D%0A++++++++++++%3Ctd+width%3D%2279%22+align%3D%22left%22+valign%3D%22middle%22%3E%3Cfont+color%3D%22%23969696%22%3E%3Ca+href%3D%22%7B%24FRONTEND_URL%7D%7Bseo+controller%3Dclientsettings%7D%22+target%3D%22_blank%22%3E%7Btrans%7DTXT_YOUR_ACCOUNT%7B/trans%7D%3C/a%3E%3C/font%3E%3C/td%3E%0D%0A++++++++++++%3Ctd+width%3D%2275%22+align%3D%22left%22+valign%3D%22middle%22%3E%3Cfont+color%3D%22%23969696%22%3E%3Ca+href%3D%22%7B%24FRONTEND_URL%7D%7Bseo+controller%3Dcontact%7D%22+target%3D%22_blank%22%3E%7Btrans%7DTXT_CONTACT%7B/trans%7D%3C/a%3E%3C/font%3E%3C/td%3E%0D%0A++++++++++++%7Belse%7D%0D%0A+++++++++++%09%3Ctd+height%3D%2296%22+align%3D%22left%22+valign%3D%22middle%22%3E%3Cimg+src%3D%27cid:logo%27+alt%3D%22Logo+Sklep+Internetowy%22/%3E%3C/td%3E%0D%0A++++++++++++%3Ctd+width%3D%2260%22+align%3D%22left%22+valign%3D%22middle%22%3E%3Cfont+color%3D%22%23969696%22%3E%3Ca+href%3D%22%7B%24URL%7D%7Bseo+controller%3Dmainside%7D%22+target%3D%22_blank%22%3E%7Btrans%7DTXT_MAINSIDE%7B/trans%7D%3C/a%3E%3C/font%3E%3C/td%3E%0D%0A++++++++++++%3Ctd+width%3D%2279%22+align%3D%22left%22+valign%3D%22middle%22%3E%3Cfont+color%3D%22%23969696%22%3E%3Ca+href%3D%22%7B%24URL%7D%7Bseo+controller%3Dclientsettings%7D%22+target%3D%22_blank%22%3E%7Btrans%7DTXT_YOUR_ACCOUNT%7B/trans%7D%3C/a%3E%3C/font%3E%3C/td%3E%0D%0A++++++++++++%3Ctd+width%3D%2275%22+align%3D%22left%22+valign%3D%22middle%22%3E%3Cfont+color%3D%22%23969696%22%3E%3Ca+href%3D%22%7B%24URL%7D%7Bseo+controller%3Dcontact%7D%22+target%3D%22_blank%22%3E%7Btrans%7DTXT_CONTACT%7B/trans%7D%3C/a%3E%3C/font%3E%3C/td%3E%0D%0A++++++++++++%7B/if%7D%0D%0A++++++++++%3C/tr%3E%0D%0A++++++++%3C/table%3E%3C/td%3E%0D%0A++++++%3C/tr%3E%0D%0A++++++%3Ctr%3E%0D%0A++++++++%3Ctd+height%3D%2223%22+align%3D%22left%22+valign%3D%22top%22%3E%3Chr+noshade%3D%22noshade%22+size%3D%221%22+color%3D%22%23e8e8e8%22/%3E%3C/td%3E%0D%0A++++++%3C/tr%3E%0D%0A++++++%3Ctr%3E%0D%0A++++++++%3Ctd+style%3D%22text-align:justify%22+align%3D%22left%22+valign%3D%22top%22%3E%3Cfont+size%3D%22%2B2%22%3E%3Cb%3E%7B%24SHOP_NAME%7D%3C/b%3E%3C/font%3E%0D%0A+++%09%09%3Cbr/%3E%7Btrans%7DTXT_HEADER_INFO%7B/trans%7D%0D%0A++++++++%3C/td%3E%0D%0A++++++%3C/tr%3E%0D%0A++++++%3Ctr%3E%0D%0A++++++++%3Ctd+height%3D%2238%22+align%3D%22left%22+valign%3D%22top%22%3E%26nbsp%3B%3C/td%3E%0D%0A++++++%3C/tr%3E%3Ctr%3E%0D%0A%09%3Ctd%3E%3Cfont+size%3D%22%2B1%22%3E%3Cb%3E%7Btrans%7DTXT_PRODUCTS%7B/trans%7D:+%3C/b%3E%3C/font%3E%3Cbr+/%3E%0D%0A%09%3Ctable%3E%0D%0A%09%09%3Cthead%3E%0D%0A%09%09%09%3Ctr%3E%0D%0A%09%09%09%09%3Cth+class%3D%22name%22%3E%7Btrans%7DTXT_PRODUCT_NAME%7B/trans%7D:%3C/th%3E%0D%0A%09%09%09%09%3Cth+class%3D%22price%22%3E%7Btrans%7DTXT_PRODUCT_PRICE%7B/trans%7D:%3C/th%3E%0D%0A%09%09%09%09%3Cth+class%3D%22quantity%22%3E%7Btrans%7DTXT_QUANTITY%7B/trans%7D:%3C/th%3E%0D%0A%09%09%09%09%3Cth+class%3D%22subtotal%22%3E%7Btrans%7DTXT_VALUE%7B/trans%7D:%3C/th%3E%0D%0A%09%09%09%3C/tr%3E%0D%0A%09%09%3C/thead%3E%0D%0A%09%09%3Ctbody%3E%0D%0A%09%09%7Bforeach+name%3Douter+item%3Dproduct+from%3D%24order.cart%7D+%0D%0A%09%09%09%7Bif+isset%28%24product.standard%29%7D%0D%0A%09%09%09%3Ctr%3E%0D%0A%09%09%09%09%3Cth%3E%7B%24product.name%7D%3C/th%3E%0D%0A%09%09%09%09%3Ctd%3E%7Bprice%7D%7B%24product.newprice%7D%7B/price%7D%3C/td%3E%0D%0A%09%09%09%09%3Ctd%3E%7B%24product.qty%7D+%7Btrans%7DTXT_QTY%7B/trans%7D%3C/td%3E%0D%0A%09%09%09%09%3Ctd%3E%7Bprice%7D%7B%24product.qtyprice%7D%7B/price%7D%3C/td%3E%0D%0A%09%09%09%3C/tr%3E%0D%0A%09%09%09%7B/if%7D%0D%0A%09%09%09%7Bforeach+name%3Dinner+item%3Dattributes+from%3D%24product.attributes%7D%0D%0A%09%09%09%09%3Ctr%3E%0D%0A%09%09%09%09%09%3Cth%3E%7B%24attributes.name%7D%3Cbr+/%3E%0D%0A%09%09%09%09%09%7Bforeach+name%3Df+item%3Dfeatures+from%3D%24attributes.features%7D+%3Csmall%3E%0D%0A%09%09%09%09%09%7B%24features.attributename%7D%26nbsp%3B%26nbsp%3B%3C/small%3E+%7B/foreach%7D%3C/th%3E%0D%0A%09%09%09%09%09%3Ctd%3E%7Bprice%7D%7B%24attributes.newprice%7D%7B/price%7D%3C/td%3E%0D%0A%09%09%09%09%09%3Ctd%3E%7B%24attributes.qty%7D+%7Btrans%7DTXT_QTY%7B/trans%7D%3C/td%3E%0D%0A%09%09%09%09%09%3Ctd%3E%7Bprice%7D%7B%24attributes.qtyprice%7D%7B/price%7D%3C/td%3E%0D%0A%09%09%09%09%3C/tr%3E%0D%0A%09%09%09%7B/foreach%7D+%0D%0A%09%09%7B/foreach%7D%0D%0A%09%09%3C/tbody%3E%0D%0A%09%3C/table%3E%0D%0A%09%3C/td%3E%0D%0A%3C/tr%3E%0D%0A%3Ctr%3E%0D%0A%09%3Ctd%3E%0D%0A%09%3Cp%3E%3Cfont+size%3D%22%2B1%22%3E%3Cb%3E%7Btrans%7DTXT_VIEW_ORDER_SUMMARY%7B/trans%7D:+%3C/b%3E%3C/font%3E%3Cbr+/%3E%0D%0A%09%7Bif+isset%28%24order.rulescart%29%7D%0D%0A%09%09%3Cp%3E%7Btrans%7DTXT_PRODUCTS%7B/trans%7D:+%3Cstrong%3E%7Bprice%7D%7B%24order.globalPrice%7D%7B/price%7D%3C/strong%3E%3C/p%3E%0D%0A%09%09%3Cp%3E%7B%24order.rulescart%7D:+%3Cstrong%3E%7B%24order.rulescartmessage%7D%3C/strong%3E%3C/p%3E%0D%0A%09%09%3Cp%3E%7B%24order.dispatchmethod.dispatchmethodname%7D:++%3Cstrong%3E%7Bprice%7D%7B%24order.dispatchmethod.dispatchmethodcost%7D%7B/price%7D%3C/strong%3E%3C/p%3E%0D%0A%09%09%3Cp%3E%7Btrans%7DTXT_VIEW_ORDER_TOTAL%7B/trans%7D:+%3Cstrong%3E%7Bprice%7D%7B%24order.priceWithDispatchMethodPromo%7D%7B/price%7D%3C/strong%3E%3C/p%3E%0D%0A%09%7Belse%7D%0D%0A%09%09%3Cp%3E%7Btrans%7DTXT_PRODUCTS%7B/trans%7D:+%3Cstrong%3E%7Bprice%7D%7B%24order.globalPrice%7D%7B/price%7D%3C/strong%3E%3C/p%3E%0D%0A%09%09%3Cp%3E%7B%24order.dispatchmethod.dispatchmethodname%7D:++%3Cstrong%3E%7Bprice%7D%7B%24order.dispatchmethod.dispatchmethodcost%7D%7B/price%7D%3C/strong%3E%3C/p%3E%0D%0A%09%09%3Cp%3E%7Btrans%7DTXT_ALL_ORDERS_PRICE_GROSS%7B/trans%7D:+%3Cstrong%3E%7Bprice%7D%7B%24order.priceWithDispatchMethod%7D%7B/price%7D%3C/strong%3E%3C/p%3E%0D%0A%09%7B/if%7D%0D%0A%09%3Cp%3E%7Btrans%7DTXT_COUNT%7B/trans%7D+:+%7B%24order.count%7D+%7Btrans%7DTXT_QTY%7B/trans%7D%3C/p%3E%0D%0A%09%3Cp%3E%7Btrans%7DTXT_METHOD_OF_PEYMENT%7B/trans%7D+:+%7B%24order.payment.paymentmethodname%7D%3C/p%3E%0D%0A%09%3C/td%3E%0D%0A%3C/tr%3E%0D%0A%7Bif+%24confirmorder+%3D%3D+1%7D%0D%0A%3Ctr%3E%0D%0A%09%3Ctd%3E%0D%0A%09%3Cp%3E%7Btrans%7DTXT_CLICK_LINK_TO_ACTIVE_ORDER%7B/trans%7D+%3Cbr+/%3E%0D%0A%09%3Ca+href%3D%22%7B%24URL%7Dconfirmation/index/%7B%24orderlink%7D%22%3E%7B%24URL%7Dconfirmation/index/%7B%24orderlink%7D%3C/a%3E%0D%0A%09%3C/p%3E%0D%0A%09%3C/td%3E%0D%0A%3C/tr%3E%0D%0A%7B/if%7D%0D%0A%3Ctr%3E%0D%0A%09%3Ctd%3E%0D%0A%09%3Cp%3E%3Cfont+size%3D%22%2B1%22%3E%3Cb%3E%7Btrans%7DTXT_CLIENT%7B/trans%7D:+%3C/b%3E%3C/font%3E%3Cbr+/%3E%0D%0A%09%7Bif+%24order.clientaddress.companyname+%21%3D%0D%0A%09%27%27%7D%7Btrans%7DTXT_COMPANYNAME%7B/trans%7D+:+%7B%24order.clientaddress.companyname%7D%0D%0A%09%3Cbr%3E%7B/if%7D+%7Bif+%24order.clientaddress.nip+%21%3D+%27%27%7D%7Btrans%7DTXT_NIP%7B/trans%7D+:%0D%0A%09%7B%24order.clientaddress.nip%7D+%0D%0A%09%0D%0A%09%0D%0A%09%3Cbr%3E%7B/if%7D+%7Btrans%7DTXT_FIRSTNAME%7B/trans%7D+:%0D%0A%09%7B%24order.clientaddress.firstname%7D+%0D%0A%09%0D%0A%09%0D%0A%09%3Cbr%3E+%7Btrans%7DTXT_SURNAME%7B/trans%7D+:+%7B%24order.clientaddress.surname%7D+%0D%0A%09%0D%0A%09%0D%0A%09%3Cbr%3E+%7Btrans%7DTXT_PLACENAME%7B/trans%7D+:+%7B%24order.clientaddress.placename%7D%0D%0A%09%0D%0A%09%0D%0A%09%3Cbr%3E+%7Btrans%7DTXT_POSTCODE%7B/trans%7D+:+%7B%24order.clientaddress.postcode%7D%0D%0A%09%0D%0A%09%0D%0A%09%3Cbr%3E+%7Btrans%7DTXT_STREET%7B/trans%7D+:+%7B%24order.clientaddress.street%7D+%0D%0A%09%0D%0A%09%0D%0A%09%3Cbr%3E+%7Btrans%7DTXT_STREETNO%7B/trans%7D+:+%7B%24order.clientaddress.streetno%7D%0D%0A%09%0D%0A%09%0D%0A%09%3Cbr%3E+%7Btrans%7DTXT_PLACENO%7B/trans%7D+:+%7B%24order.clientaddress.placeno%7D%0D%0A%09%0D%0A%09%0D%0A%09%3Cbr%3E+%7Btrans%7DTXT_PHONE%7B/trans%7D+:+%7B%24order.clientaddress.phone%7D%0D%0A%09%0D%0A%09%0D%0A%09%3Cbr%3E+%7Btrans%7DTXT_EMAIL%7B/trans%7D+:+%7B%24order.clientaddress.email%7D%0D%0A%09%0D%0A%09%0D%0A%09%3Cbr%3E%0D%0A%09%3C/p%3E%0D%0A%09%3C/td%3E%0D%0A%3C/tr%3E%0D%0A%3Ctr%3E%0D%0A%09%3Ctd%3E%0D%0A%09%3Cp%3E%3Cfont+size%3D%22%2B1%22%3E%3Cb%3E%7Btrans%7DTXT_DELIVERER_ADDRESS%7B/trans%7D:+%3C/b%3E%3C/font%3E%3Cbr+/%3E%0D%0A%09%7Btrans%7DTXT_FIRSTNAME%7B/trans%7D+:+%7B%24order.deliveryAddress.firstname%7D+%3Cbr%3E%0D%0A%09%7Btrans%7DTXT_SURNAME%7B/trans%7D+:+%7B%24order.deliveryAddress.surname%7D+%0D%0A%09%0D%0A%09%0D%0A%09%3Cbr%3E+%7Btrans%7DTXT_PLACENAME%7B/trans%7D+:+%7B%24order.deliveryAddress.placename%7D%0D%0A%09%0D%0A%09%0D%0A%09%3Cbr%3E+%7Btrans%7DTXT_POSTCODE%7B/trans%7D+:+%7B%24order.deliveryAddress.postcode%7D%0D%0A%09%0D%0A%09%0D%0A%09%3Cbr%3E+%7Btrans%7DTXT_STREET%7B/trans%7D+:+%7B%24order.deliveryAddress.street%7D+%0D%0A%09%0D%0A%09%0D%0A%09%3Cbr%3E+%7Btrans%7DTXT_STREETNO%7B/trans%7D+:+%7B%24order.deliveryAddress.streetno%7D%0D%0A%09%0D%0A%09%0D%0A%09%3Cbr%3E+%7Btrans%7DTXT_PLACENO%7B/trans%7D+:+%7B%24order.deliveryAddress.placeno%7D%0D%0A%09%0D%0A%09%0D%0A%09%3Cbr%3E+%7Btrans%7DTXT_PHONE%7B/trans%7D+:+%7B%24order.deliveryAddress.phone%7D%0D%0A%09%0D%0A%09%0D%0A%09%3Cbr%3E+%7Btrans%7DTXT_EMAIL%7B/trans%7D+:+%7B%24order.deliveryAddress.email%7D%0D%0A%09%0D%0A%09%0D%0A%09%3Cbr%3E%0D%0A%09%3C/p%3E%0D%0A%09%3C/td%3E%0D%0A%3C/tr%3E%0D%0A%3Ctr%3E%0D%0A%09%3Ctd%3E%3Cfont+size%3D%22%2B1%22%3E%3Cb%3E%7Btrans%7DTXT_PRODUCT_REVIEW%7B/trans%7D:+%3C/b%3E%3C/font%3E%3Cbr+/%3E%0D%0A%09%3Cp%3E%7B%24order.customeropinion%7D%3C/p%3E%0D%0A%09%3C/td%3E%0D%0A%3C/tr%3E%0D%0A%7Bif+isset%28%24orderfiles%29%7D%0D%0A%7Bforeach+from%3D%24orderfiles+item%3Dfile+key%3Dkey%7D%0D%0A%3Ctr%3E%0D%0A%09%3Ctd%3E%3Ca+href%3D%22%7B%24URL%7Dupload/order/%7B%24key%7D%22%3E%7B%24URL%7Dupload/order/%7B%24key%7D%3C/a%3E%3C/td%3E%0D%0A%3C/tr%3E%0D%0A%7B/foreach%7D%0D%0A%7B/if%7D%0D%0A+%3Ctr%3E%0D%0A++++++++%3Ctd+height%3D%2220%22+align%3D%22left%22+valign%3D%22top%22+style%3D%22text-align:justify%22%3E%26nbsp%3B%3C/td%3E%0D%0A++++++%3C/tr%3E%0D%0A++++%3C/table%3E%0D%0A+++%3C/td%3E%0D%0A++++%3Ctd%3E%26nbsp%3B%3C/td%3E%0D%0A++%3C/tr%3E%0D%0A++%3Ctr%3E%0D%0A++++%3Ctd+height%3D%2210%22+bgcolor%3D%22%233d3d3d%22%3E%26nbsp%3B%3C/td%3E%0D%0A++++%3Ctd+width%3D%22500%22+height%3D%2210%22+align%3D%22left%22+valign%3D%22top%22+bgcolor%3D%22%233d3d3d%22%3E%26nbsp%3B%3C/td%3E%0D%0A++++%3Ctd+height%3D%2210%22+bgcolor%3D%22%233d3d3d%22%3E%26nbsp%3B%3C/td%3E%0D%0A++%3C/tr%3E%0D%0A++%3Ctr%3E%0D%0A++++%3Ctd+height%3D%2270%22+bgcolor%3D%22%232c2c2c%22%3E%26nbsp%3B%3C/td%3E%0D%0A++++%3Ctd+width%3D%22500%22+height%3D%2270%22+align%3D%22center%22+valign%3D%22middle%22+bgcolor%3D%22%232c2c2c%22%3E%0D%0A++++%3Cfont+color%3D%22%23b1b1b1%22%3E%7Btrans%7DTXT_FOOTER_EMAIL%7B/trans%7D%3C/font%3E%3C/td%3E%0D%0A++++%3Ctd+height%3D%2270%22+bgcolor%3D%22%232c2c2c%22%3E%26nbsp%3B%3C/td%3E%0D%0A++%3C/tr%3E%0D%0A%3C/table%3E%0D%0A%3C/body%3E%0D%0A%3C/html%3E */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'seo', 'text:<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{trans}MAIL_TITLE{/trans}</title>
	<style type="text/css">
	{literal}
		body,td,th {
			font-size: 11px;
			color: #575656;
			font-family: Arial;
		}
		body {
			margin-left: 0px;
			margin-top: 0px;
			margin-right: 0px;
			margin-bottom: 0px;
		}
		a:link {
			color: #BF3131;
			text-decoration: none;
		}
		a:visited {
			text-decoration: none;
			color: #BF3131;
		}
		a:hover {
			text-decoration: none;
			color: #BF3131;
		}
		a:active {
			text-decoration: none;
			color: #BF3131;
		}
	{/literal}
	</style>
</head>

<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>&nbsp;</td>
    <td width="500" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td height="96" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
          	{if isset($FRONTEND_URL)}
            <td height="96" align="left" valign="middle"><img src=\'cid:logo\' alt="Logo Sklep Internetowy"/></td>
            <td width="60" align="left" valign="middle"><font color="#969696"><a href="{$FRONTEND_URL}{seo controller=mainside}" target="_blank">{trans}TXT_MAINSIDE{/trans}</a></font></td>
            <td width="79" align="left" valign="middle"><font color="#969696"><a href="{$FRONTEND_URL}{seo controller=clientsettings}" target="_blank">{trans}TXT_YOUR_ACCOUNT{/trans}</a></font></td>
            <td width="75" align="left" valign="middle"><font color="#969696"><a href="{$FRONTEND_URL}{seo controller=contact}" target="_blank">{trans}TXT_CONTACT{/trans}</a></font></td>
            {else}
           	<td height="96" align="left" valign="middle"><img src=\'cid:logo\' alt="Logo Sklep Internetowy"/></td>
            <td width="60" align="left" valign="middle"><font color="#969696"><a href="{$URL}{seo controller=mainside}" target="_blank">{trans}TXT_MAINSIDE{/trans}</a></font></td>
            <td width="79" align="left" valign="middle"><font color="#969696"><a href="{$URL}{seo controller=clientsettings}" target="_blank">{trans}TXT_YOUR_ACCOUNT{/trans}</a></font></td>
            <td width="75" align="left" valign="middle"><font color="#969696"><a href="{$URL}{seo controller=contact}" target="_blank">{trans}TXT_CONTACT{/trans}</a></font></td>
            {/if}
          </tr>
        </table></td>
      </tr>
      <tr>
        <td height="23" align="left" valign="top"><hr noshade="noshade" size="1" color="#e8e8e8"/></td>
      </tr>
      <tr>
        <td style="text-align:justify" align="left" valign="top"><font size="+2"><b>{$SHOP_NAME}</b></font>
   		<br/>{trans}TXT_HEADER_INFO{/trans}
        </td>
      </tr>
      <tr>
        <td height="38" align="left" valign="top">&nbsp;</td>
      </tr><tr>
	<td><font size="+1"><b>{trans}TXT_PRODUCTS{/trans}: </b></font><br />
	<table>
		<thead>
			<tr>
				<th class="name">{trans}TXT_PRODUCT_NAME{/trans}:</th>
				<th class="price">{trans}TXT_PRODUCT_PRICE{/trans}:</th>
				<th class="quantity">{trans}TXT_QUANTITY{/trans}:</th>
				<th class="subtotal">{trans}TXT_VALUE{/trans}:</th>
			</tr>
		</thead>
		<tbody>
		{foreach name=outer item=product from=$order.cart} 
			{if isset($product.standard)}
			<tr>
				<th>{$product.name}</th>
				<td>{price}{$product.newprice}{/price}</td>
				<td>{$product.qty} {trans}TXT_QTY{/trans}</td>
				<td>{price}{$product.qtyprice}{/price}</td>
			</tr>
			{/if}
			{foreach name=inner item=attributes from=$product.attributes}
				<tr>
					<th>{$attributes.name}<br />
					{foreach name=f item=features from=$attributes.features} <small>
					{$features.attributename}&nbsp;&nbsp;</small> {/foreach}</th>
					<td>{price}{$attributes.newprice}{/price}</td>
					<td>{$attributes.qty} {trans}TXT_QTY{/trans}</td>
					<td>{price}{$attributes.qtyprice}{/price}</td>
				</tr>
			{/foreach} 
		{/foreach}
		</tbody>
	</table>
	</td>
</tr>
<tr>
	<td>
	<p><font size="+1"><b>{trans}TXT_VIEW_ORDER_SUMMARY{/trans}: </b></font><br />
	{if isset($order.rulescart)}
		<p>{trans}TXT_PRODUCTS{/trans}: <strong>{price}{$order.globalPrice}{/price}</strong></p>
		<p>{$order.rulescart}: <strong>{$order.rulescartmessage}</strong></p>
		<p>{$order.dispatchmethod.dispatchmethodname}:  <strong>{price}{$order.dispatchmethod.dispatchmethodcost}{/price}</strong></p>
		<p>{trans}TXT_VIEW_ORDER_TOTAL{/trans}: <strong>{price}{$order.priceWithDispatchMethodPromo}{/price}</strong></p>
	{else}
		<p>{trans}TXT_PRODUCTS{/trans}: <strong>{price}{$order.globalPrice}{/price}</strong></p>
		<p>{$order.dispatchmethod.dispatchmethodname}:  <strong>{price}{$order.dispatchmethod.dispatchmethodcost}{/price}</strong></p>
		<p>{trans}TXT_ALL_ORDERS_PRICE_GROSS{/trans}: <strong>{price}{$order.priceWithDispatchMethod}{/price}</strong></p>
	{/if}
	<p>{trans}TXT_COUNT{/trans} : {$order.count} {trans}TXT_QTY{/trans}</p>
	<p>{trans}TXT_METHOD_OF_PEYMENT{/trans} : {$order.payment.paymentmethodname}</p>
	</td>
</tr>
{if $confirmorder == 1}
<tr>
	<td>
	<p>{trans}TXT_CLICK_LINK_TO_ACTIVE_ORDER{/trans} <br />
	<a href="{$URL}confirmation/index/{$orderlink}">{$URL}confirmation/index/{$orderlink}</a>
	</p>
	</td>
</tr>
{/if}
<tr>
	<td>
	<p><font size="+1"><b>{trans}TXT_CLIENT{/trans}: </b></font><br />
	{if $order.clientaddress.companyname !=
	\'\'}{trans}TXT_COMPANYNAME{/trans} : {$order.clientaddress.companyname}
	<br>{/if} {if $order.clientaddress.nip != \'\'}{trans}TXT_NIP{/trans} :
	{$order.clientaddress.nip} 
	
	
	<br>{/if} {trans}TXT_FIRSTNAME{/trans} :
	{$order.clientaddress.firstname} 
	
	
	<br> {trans}TXT_SURNAME{/trans} : {$order.clientaddress.surname} 
	
	
	<br> {trans}TXT_PLACENAME{/trans} : {$order.clientaddress.placename}
	
	
	<br> {trans}TXT_POSTCODE{/trans} : {$order.clientaddress.postcode}
	
	
	<br> {trans}TXT_STREET{/trans} : {$order.clientaddress.street} 
	
	
	<br> {trans}TXT_STREETNO{/trans} : {$order.clientaddress.streetno}
	
	
	<br> {trans}TXT_PLACENO{/trans} : {$order.clientaddress.placeno}
	
	
	<br> {trans}TXT_PHONE{/trans} : {$order.clientaddress.phone}
	
	
	<br> {trans}TXT_EMAIL{/trans} : {$order.clientaddress.email}
	
	
	<br>
	</p>
	</td>
</tr>
<tr>
	<td>
	<p><font size="+1"><b>{trans}TXT_DELIVERER_ADDRESS{/trans}: </b></font><br />
	{trans}TXT_FIRSTNAME{/trans} : {$order.deliveryAddress.firstname} <br>
	{trans}TXT_SURNAME{/trans} : {$order.deliveryAddress.surname} 
	
	
	<br> {trans}TXT_PLACENAME{/trans} : {$order.deliveryAddress.placename}
	
	
	<br> {trans}TXT_POSTCODE{/trans} : {$order.deliveryAddress.postcode}
	
	
	<br> {trans}TXT_STREET{/trans} : {$order.deliveryAddress.street} 
	
	
	<br> {trans}TXT_STREETNO{/trans} : {$order.deliveryAddress.streetno}
	
	
	<br> {trans}TXT_PLACENO{/trans} : {$order.deliveryAddress.placeno}
	
	
	<br> {trans}TXT_PHONE{/trans} : {$order.deliveryAddress.phone}
	
	
	<br> {trans}TXT_EMAIL{/trans} : {$order.deliveryAddress.email}
	
	
	<br>
	</p>
	</td>
</tr>
<tr>
	<td><font size="+1"><b>{trans}TXT_PRODUCT_REVIEW{/trans}: </b></font><br />
	<p>{$order.customeropinion}</p>
	</td>
</tr>
{if isset($orderfiles)}
{foreach from=$orderfiles item=file key=key}
<tr>
	<td><a href="{$URL}upload/order/{$key}">{$URL}upload/order/{$key}</a></td>
</tr>
{/foreach}
{/if}
 <tr>
        <td height="20" align="left" valign="top" style="text-align:justify">&nbsp;</td>
      </tr>
    </table>
   </td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td height="10" bgcolor="#3d3d3d">&nbsp;</td>
    <td width="500" height="10" align="left" valign="top" bgcolor="#3d3d3d">&nbsp;</td>
    <td height="10" bgcolor="#3d3d3d">&nbsp;</td>
  </tr>
  <tr>
    <td height="70" bgcolor="#2c2c2c">&nbsp;</td>
    <td width="500" height="70" align="center" valign="middle" bgcolor="#2c2c2c">
    <font color="#b1b1b1">{trans}TXT_FOOTER_EMAIL{/trans}</font></td>
    <td height="70" bgcolor="#2c2c2c">&nbsp;</td>
  </tr>
</table>
</body>
</html>', 49, false),array('block', 'price', 'text:<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{trans}MAIL_TITLE{/trans}</title>
	<style type="text/css">
	{literal}
		body,td,th {
			font-size: 11px;
			color: #575656;
			font-family: Arial;
		}
		body {
			margin-left: 0px;
			margin-top: 0px;
			margin-right: 0px;
			margin-bottom: 0px;
		}
		a:link {
			color: #BF3131;
			text-decoration: none;
		}
		a:visited {
			text-decoration: none;
			color: #BF3131;
		}
		a:hover {
			text-decoration: none;
			color: #BF3131;
		}
		a:active {
			text-decoration: none;
			color: #BF3131;
		}
	{/literal}
	</style>
</head>

<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>&nbsp;</td>
    <td width="500" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td height="96" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
          	{if isset($FRONTEND_URL)}
            <td height="96" align="left" valign="middle"><img src=\'cid:logo\' alt="Logo Sklep Internetowy"/></td>
            <td width="60" align="left" valign="middle"><font color="#969696"><a href="{$FRONTEND_URL}{seo controller=mainside}" target="_blank">{trans}TXT_MAINSIDE{/trans}</a></font></td>
            <td width="79" align="left" valign="middle"><font color="#969696"><a href="{$FRONTEND_URL}{seo controller=clientsettings}" target="_blank">{trans}TXT_YOUR_ACCOUNT{/trans}</a></font></td>
            <td width="75" align="left" valign="middle"><font color="#969696"><a href="{$FRONTEND_URL}{seo controller=contact}" target="_blank">{trans}TXT_CONTACT{/trans}</a></font></td>
            {else}
           	<td height="96" align="left" valign="middle"><img src=\'cid:logo\' alt="Logo Sklep Internetowy"/></td>
            <td width="60" align="left" valign="middle"><font color="#969696"><a href="{$URL}{seo controller=mainside}" target="_blank">{trans}TXT_MAINSIDE{/trans}</a></font></td>
            <td width="79" align="left" valign="middle"><font color="#969696"><a href="{$URL}{seo controller=clientsettings}" target="_blank">{trans}TXT_YOUR_ACCOUNT{/trans}</a></font></td>
            <td width="75" align="left" valign="middle"><font color="#969696"><a href="{$URL}{seo controller=contact}" target="_blank">{trans}TXT_CONTACT{/trans}</a></font></td>
            {/if}
          </tr>
        </table></td>
      </tr>
      <tr>
        <td height="23" align="left" valign="top"><hr noshade="noshade" size="1" color="#e8e8e8"/></td>
      </tr>
      <tr>
        <td style="text-align:justify" align="left" valign="top"><font size="+2"><b>{$SHOP_NAME}</b></font>
   		<br/>{trans}TXT_HEADER_INFO{/trans}
        </td>
      </tr>
      <tr>
        <td height="38" align="left" valign="top">&nbsp;</td>
      </tr><tr>
	<td><font size="+1"><b>{trans}TXT_PRODUCTS{/trans}: </b></font><br />
	<table>
		<thead>
			<tr>
				<th class="name">{trans}TXT_PRODUCT_NAME{/trans}:</th>
				<th class="price">{trans}TXT_PRODUCT_PRICE{/trans}:</th>
				<th class="quantity">{trans}TXT_QUANTITY{/trans}:</th>
				<th class="subtotal">{trans}TXT_VALUE{/trans}:</th>
			</tr>
		</thead>
		<tbody>
		{foreach name=outer item=product from=$order.cart} 
			{if isset($product.standard)}
			<tr>
				<th>{$product.name}</th>
				<td>{price}{$product.newprice}{/price}</td>
				<td>{$product.qty} {trans}TXT_QTY{/trans}</td>
				<td>{price}{$product.qtyprice}{/price}</td>
			</tr>
			{/if}
			{foreach name=inner item=attributes from=$product.attributes}
				<tr>
					<th>{$attributes.name}<br />
					{foreach name=f item=features from=$attributes.features} <small>
					{$features.attributename}&nbsp;&nbsp;</small> {/foreach}</th>
					<td>{price}{$attributes.newprice}{/price}</td>
					<td>{$attributes.qty} {trans}TXT_QTY{/trans}</td>
					<td>{price}{$attributes.qtyprice}{/price}</td>
				</tr>
			{/foreach} 
		{/foreach}
		</tbody>
	</table>
	</td>
</tr>
<tr>
	<td>
	<p><font size="+1"><b>{trans}TXT_VIEW_ORDER_SUMMARY{/trans}: </b></font><br />
	{if isset($order.rulescart)}
		<p>{trans}TXT_PRODUCTS{/trans}: <strong>{price}{$order.globalPrice}{/price}</strong></p>
		<p>{$order.rulescart}: <strong>{$order.rulescartmessage}</strong></p>
		<p>{$order.dispatchmethod.dispatchmethodname}:  <strong>{price}{$order.dispatchmethod.dispatchmethodcost}{/price}</strong></p>
		<p>{trans}TXT_VIEW_ORDER_TOTAL{/trans}: <strong>{price}{$order.priceWithDispatchMethodPromo}{/price}</strong></p>
	{else}
		<p>{trans}TXT_PRODUCTS{/trans}: <strong>{price}{$order.globalPrice}{/price}</strong></p>
		<p>{$order.dispatchmethod.dispatchmethodname}:  <strong>{price}{$order.dispatchmethod.dispatchmethodcost}{/price}</strong></p>
		<p>{trans}TXT_ALL_ORDERS_PRICE_GROSS{/trans}: <strong>{price}{$order.priceWithDispatchMethod}{/price}</strong></p>
	{/if}
	<p>{trans}TXT_COUNT{/trans} : {$order.count} {trans}TXT_QTY{/trans}</p>
	<p>{trans}TXT_METHOD_OF_PEYMENT{/trans} : {$order.payment.paymentmethodname}</p>
	</td>
</tr>
{if $confirmorder == 1}
<tr>
	<td>
	<p>{trans}TXT_CLICK_LINK_TO_ACTIVE_ORDER{/trans} <br />
	<a href="{$URL}confirmation/index/{$orderlink}">{$URL}confirmation/index/{$orderlink}</a>
	</p>
	</td>
</tr>
{/if}
<tr>
	<td>
	<p><font size="+1"><b>{trans}TXT_CLIENT{/trans}: </b></font><br />
	{if $order.clientaddress.companyname !=
	\'\'}{trans}TXT_COMPANYNAME{/trans} : {$order.clientaddress.companyname}
	<br>{/if} {if $order.clientaddress.nip != \'\'}{trans}TXT_NIP{/trans} :
	{$order.clientaddress.nip} 
	
	
	<br>{/if} {trans}TXT_FIRSTNAME{/trans} :
	{$order.clientaddress.firstname} 
	
	
	<br> {trans}TXT_SURNAME{/trans} : {$order.clientaddress.surname} 
	
	
	<br> {trans}TXT_PLACENAME{/trans} : {$order.clientaddress.placename}
	
	
	<br> {trans}TXT_POSTCODE{/trans} : {$order.clientaddress.postcode}
	
	
	<br> {trans}TXT_STREET{/trans} : {$order.clientaddress.street} 
	
	
	<br> {trans}TXT_STREETNO{/trans} : {$order.clientaddress.streetno}
	
	
	<br> {trans}TXT_PLACENO{/trans} : {$order.clientaddress.placeno}
	
	
	<br> {trans}TXT_PHONE{/trans} : {$order.clientaddress.phone}
	
	
	<br> {trans}TXT_EMAIL{/trans} : {$order.clientaddress.email}
	
	
	<br>
	</p>
	</td>
</tr>
<tr>
	<td>
	<p><font size="+1"><b>{trans}TXT_DELIVERER_ADDRESS{/trans}: </b></font><br />
	{trans}TXT_FIRSTNAME{/trans} : {$order.deliveryAddress.firstname} <br>
	{trans}TXT_SURNAME{/trans} : {$order.deliveryAddress.surname} 
	
	
	<br> {trans}TXT_PLACENAME{/trans} : {$order.deliveryAddress.placename}
	
	
	<br> {trans}TXT_POSTCODE{/trans} : {$order.deliveryAddress.postcode}
	
	
	<br> {trans}TXT_STREET{/trans} : {$order.deliveryAddress.street} 
	
	
	<br> {trans}TXT_STREETNO{/trans} : {$order.deliveryAddress.streetno}
	
	
	<br> {trans}TXT_PLACENO{/trans} : {$order.deliveryAddress.placeno}
	
	
	<br> {trans}TXT_PHONE{/trans} : {$order.deliveryAddress.phone}
	
	
	<br> {trans}TXT_EMAIL{/trans} : {$order.deliveryAddress.email}
	
	
	<br>
	</p>
	</td>
</tr>
<tr>
	<td><font size="+1"><b>{trans}TXT_PRODUCT_REVIEW{/trans}: </b></font><br />
	<p>{$order.customeropinion}</p>
	</td>
</tr>
{if isset($orderfiles)}
{foreach from=$orderfiles item=file key=key}
<tr>
	<td><a href="{$URL}upload/order/{$key}">{$URL}upload/order/{$key}</a></td>
</tr>
{/foreach}
{/if}
 <tr>
        <td height="20" align="left" valign="top" style="text-align:justify">&nbsp;</td>
      </tr>
    </table>
   </td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td height="10" bgcolor="#3d3d3d">&nbsp;</td>
    <td width="500" height="10" align="left" valign="top" bgcolor="#3d3d3d">&nbsp;</td>
    <td height="10" bgcolor="#3d3d3d">&nbsp;</td>
  </tr>
  <tr>
    <td height="70" bgcolor="#2c2c2c">&nbsp;</td>
    <td width="500" height="70" align="center" valign="middle" bgcolor="#2c2c2c">
    <font color="#b1b1b1">{trans}TXT_FOOTER_EMAIL{/trans}</font></td>
    <td height="70" bgcolor="#2c2c2c">&nbsp;</td>
  </tr>
</table>
</body>
</html>', 87, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>MAIL_TITLE</title>
	<style type="text/css">
	<?php echo '
		body,td,th {
			font-size: 11px;
			color: #575656;
			font-family: Arial;
		}
		body {
			margin-left: 0px;
			margin-top: 0px;
			margin-right: 0px;
			margin-bottom: 0px;
		}
		a:link {
			color: #BF3131;
			text-decoration: none;
		}
		a:visited {
			text-decoration: none;
			color: #BF3131;
		}
		a:hover {
			text-decoration: none;
			color: #BF3131;
		}
		a:active {
			text-decoration: none;
			color: #BF3131;
		}
	'; ?>

	</style>
</head>

<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>&nbsp;</td>
    <td width="500" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td height="96" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
          	<?php if (isset ( $this->_tpl_vars['FRONTEND_URL'] )): ?>
            <td height="96" align="left" valign="middle"><img src='cid:logo' alt="Logo Sklep Internetowy"/></td>
            <td width="60" align="left" valign="middle"><font color="#969696"><a href="<?php echo $this->_tpl_vars['FRONTEND_URL']; ?>
<?php echo smarty_function_seo(array('controller' => 'mainside'), $this);?>
" target="_blank">Strona główna</a></font></td>
            <td width="79" align="left" valign="middle"><font color="#969696"><a href="<?php echo $this->_tpl_vars['FRONTEND_URL']; ?>
<?php echo smarty_function_seo(array('controller' => 'clientsettings'), $this);?>
" target="_blank">Twoje konto</a></font></td>
            <td width="75" align="left" valign="middle"><font color="#969696"><a href="<?php echo $this->_tpl_vars['FRONTEND_URL']; ?>
<?php echo smarty_function_seo(array('controller' => 'contact'), $this);?>
" target="_blank">Kontakt</a></font></td>
            <?php else: ?>
           	<td height="96" align="left" valign="middle"><img src='cid:logo' alt="Logo Sklep Internetowy"/></td>
            <td width="60" align="left" valign="middle"><font color="#969696"><a href="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo smarty_function_seo(array('controller' => 'mainside'), $this);?>
" target="_blank">Strona główna</a></font></td>
            <td width="79" align="left" valign="middle"><font color="#969696"><a href="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo smarty_function_seo(array('controller' => 'clientsettings'), $this);?>
" target="_blank">Twoje konto</a></font></td>
            <td width="75" align="left" valign="middle"><font color="#969696"><a href="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo smarty_function_seo(array('controller' => 'contact'), $this);?>
" target="_blank">Kontakt</a></font></td>
            <?php endif; ?>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td height="23" align="left" valign="top"><hr noshade="noshade" size="1" color="#e8e8e8"/></td>
      </tr>
      <tr>
        <td style="text-align:justify" align="left" valign="top"><font size="+2"><b><?php echo $this->_tpl_vars['SHOP_NAME']; ?>
</b></font>
   		<br/>Wszelkie informacje dotyczące zamówienia i pracy sklepu internetowego można uzyskać w godzinach 10.00-18.00 od poniedziałku do piątku oraz w soboty w godzinach 10.00-12.00
        </td>
      </tr>
      <tr>
        <td height="38" align="left" valign="top">&nbsp;</td>
      </tr><tr>
	<td><font size="+1"><b>Produkty: </b></font><br />
	<table>
		<thead>
			<tr>
				<th class="name">Nazwa produktu:</th>
				<th class="price">Cena szt:</th>
				<th class="quantity">Ilość:</th>
				<th class="subtotal">Wartość:</th>
			</tr>
		</thead>
		<tbody>
		<?php $_from = $this->_tpl_vars['order']['cart']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['outer'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['outer']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['product']):
        $this->_foreach['outer']['iteration']++;
?> 
			<?php if (isset ( $this->_tpl_vars['product']['standard'] )): ?>
			<tr>
				<th><?php echo $this->_tpl_vars['product']['name']; ?>
</th>
				<td><?php $this->_tag_stack[] = array('price', array()); $_block_repeat=true;$this->_plugins['block']['price'][0][0]->do_parse_price($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><?php echo $this->_tpl_vars['product']['newprice']; ?>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['price'][0][0]->do_parse_price($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></td>
				<td><?php echo $this->_tpl_vars['product']['qty']; ?>
 szt</td>
				<td><?php $this->_tag_stack[] = array('price', array()); $_block_repeat=true;$this->_plugins['block']['price'][0][0]->do_parse_price($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><?php echo $this->_tpl_vars['product']['qtyprice']; ?>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['price'][0][0]->do_parse_price($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></td>
			</tr>
			<?php endif; ?>
			<?php $_from = $this->_tpl_vars['product']['attributes']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['inner'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['inner']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['attributes']):
        $this->_foreach['inner']['iteration']++;
?>
				<tr>
					<th><?php echo $this->_tpl_vars['attributes']['name']; ?>
<br />
					<?php $_from = $this->_tpl_vars['attributes']['features']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['f'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['f']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['features']):
        $this->_foreach['f']['iteration']++;
?> <small>
					<?php echo $this->_tpl_vars['features']['attributename']; ?>
&nbsp;&nbsp;</small> <?php endforeach; endif; unset($_from); ?></th>
					<td><?php $this->_tag_stack[] = array('price', array()); $_block_repeat=true;$this->_plugins['block']['price'][0][0]->do_parse_price($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><?php echo $this->_tpl_vars['attributes']['newprice']; ?>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['price'][0][0]->do_parse_price($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></td>
					<td><?php echo $this->_tpl_vars['attributes']['qty']; ?>
 szt</td>
					<td><?php $this->_tag_stack[] = array('price', array()); $_block_repeat=true;$this->_plugins['block']['price'][0][0]->do_parse_price($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><?php echo $this->_tpl_vars['attributes']['qtyprice']; ?>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['price'][0][0]->do_parse_price($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></td>
				</tr>
			<?php endforeach; endif; unset($_from); ?> 
		<?php endforeach; endif; unset($_from); ?>
		</tbody>
	</table>
	</td>
</tr>
<tr>
	<td>
	<p><font size="+1"><b>Podsumowanie: </b></font><br />
	<?php if (isset ( $this->_tpl_vars['order']['rulescart'] )): ?>
		<p>Produkty: <strong><?php $this->_tag_stack[] = array('price', array()); $_block_repeat=true;$this->_plugins['block']['price'][0][0]->do_parse_price($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><?php echo $this->_tpl_vars['order']['globalPrice']; ?>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['price'][0][0]->do_parse_price($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></strong></p>
		<p><?php echo $this->_tpl_vars['order']['rulescart']; ?>
: <strong><?php echo $this->_tpl_vars['order']['rulescartmessage']; ?>
</strong></p>
		<p><?php echo $this->_tpl_vars['order']['dispatchmethod']['dispatchmethodname']; ?>
:  <strong><?php $this->_tag_stack[] = array('price', array()); $_block_repeat=true;$this->_plugins['block']['price'][0][0]->do_parse_price($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><?php echo $this->_tpl_vars['order']['dispatchmethod']['dispatchmethodcost']; ?>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['price'][0][0]->do_parse_price($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></strong></p>
		<p>Suma: <strong><?php $this->_tag_stack[] = array('price', array()); $_block_repeat=true;$this->_plugins['block']['price'][0][0]->do_parse_price($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><?php echo $this->_tpl_vars['order']['priceWithDispatchMethodPromo']; ?>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['price'][0][0]->do_parse_price($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></strong></p>
	<?php else: ?>
		<p>Produkty: <strong><?php $this->_tag_stack[] = array('price', array()); $_block_repeat=true;$this->_plugins['block']['price'][0][0]->do_parse_price($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><?php echo $this->_tpl_vars['order']['globalPrice']; ?>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['price'][0][0]->do_parse_price($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></strong></p>
		<p><?php echo $this->_tpl_vars['order']['dispatchmethod']['dispatchmethodname']; ?>
:  <strong><?php $this->_tag_stack[] = array('price', array()); $_block_repeat=true;$this->_plugins['block']['price'][0][0]->do_parse_price($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><?php echo $this->_tpl_vars['order']['dispatchmethod']['dispatchmethodcost']; ?>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['price'][0][0]->do_parse_price($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></strong></p>
		<p>Wartość zamówienia brutto: <strong><?php $this->_tag_stack[] = array('price', array()); $_block_repeat=true;$this->_plugins['block']['price'][0][0]->do_parse_price($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><?php echo $this->_tpl_vars['order']['priceWithDispatchMethod']; ?>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['price'][0][0]->do_parse_price($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></strong></p>
	<?php endif; ?>
	<p>Ilość produktów : <?php echo $this->_tpl_vars['order']['count']; ?>
 szt</p>
	<p>Sposób płatności : <?php echo $this->_tpl_vars['order']['payment']['paymentmethodname']; ?>
</p>
	</td>
</tr>
<?php if ($this->_tpl_vars['confirmorder'] == 1): ?>
<tr>
	<td>
	<p>Kliknij w link, aby potwierdzić zamówienie. <br />
	<a href="<?php echo $this->_tpl_vars['URL']; ?>
confirmation/index/<?php echo $this->_tpl_vars['orderlink']; ?>
"><?php echo $this->_tpl_vars['URL']; ?>
confirmation/index/<?php echo $this->_tpl_vars['orderlink']; ?>
</a>
	</p>
	</td>
</tr>
<?php endif; ?>
<tr>
	<td>
	<p><font size="+1"><b>Dane klienta: </b></font><br />
	<?php if ($this->_tpl_vars['order']['clientaddress']['companyname'] != ''): ?>Nazwa firmy : <?php echo $this->_tpl_vars['order']['clientaddress']['companyname']; ?>

	<br><?php endif; ?> <?php if ($this->_tpl_vars['order']['clientaddress']['nip'] != ''): ?>NIP :
	<?php echo $this->_tpl_vars['order']['clientaddress']['nip']; ?>
 
	
	
	<br><?php endif; ?> Imię :
	<?php echo $this->_tpl_vars['order']['clientaddress']['firstname']; ?>
 
	
	
	<br> Nazwisko : <?php echo $this->_tpl_vars['order']['clientaddress']['surname']; ?>
 
	
	
	<br> Miasto : <?php echo $this->_tpl_vars['order']['clientaddress']['placename']; ?>

	
	
	<br> Kod pocztowy : <?php echo $this->_tpl_vars['order']['clientaddress']['postcode']; ?>

	
	
	<br> Ulica : <?php echo $this->_tpl_vars['order']['clientaddress']['street']; ?>
 
	
	
	<br> Nr budynku : <?php echo $this->_tpl_vars['order']['clientaddress']['streetno']; ?>

	
	
	<br> Nr lokalu : <?php echo $this->_tpl_vars['order']['clientaddress']['placeno']; ?>

	
	
	<br> Telefon : <?php echo $this->_tpl_vars['order']['clientaddress']['phone']; ?>

	
	
	<br> E-mail : <?php echo $this->_tpl_vars['order']['clientaddress']['email']; ?>

	
	
	<br>
	</p>
	</td>
</tr>
<tr>
	<td>
	<p><font size="+1"><b>Adres dostawy: </b></font><br />
	Imię : <?php echo $this->_tpl_vars['order']['deliveryAddress']['firstname']; ?>
 <br>
	Nazwisko : <?php echo $this->_tpl_vars['order']['deliveryAddress']['surname']; ?>
 
	
	
	<br> Miasto : <?php echo $this->_tpl_vars['order']['deliveryAddress']['placename']; ?>

	
	
	<br> Kod pocztowy : <?php echo $this->_tpl_vars['order']['deliveryAddress']['postcode']; ?>

	
	
	<br> Ulica : <?php echo $this->_tpl_vars['order']['deliveryAddress']['street']; ?>
 
	
	
	<br> Nr budynku : <?php echo $this->_tpl_vars['order']['deliveryAddress']['streetno']; ?>

	
	
	<br> Nr lokalu : <?php echo $this->_tpl_vars['order']['deliveryAddress']['placeno']; ?>

	
	
	<br> Telefon : <?php echo $this->_tpl_vars['order']['deliveryAddress']['phone']; ?>

	
	
	<br> E-mail : <?php echo $this->_tpl_vars['order']['deliveryAddress']['email']; ?>

	
	
	<br>
	</p>
	</td>
</tr>
<tr>
	<td><font size="+1"><b>Komentarze: </b></font><br />
	<p><?php echo $this->_tpl_vars['order']['customeropinion']; ?>
</p>
	</td>
</tr>
<?php if (isset ( $this->_tpl_vars['orderfiles'] )): ?>
<?php $_from = $this->_tpl_vars['orderfiles']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['file']):
?>
<tr>
	<td><a href="<?php echo $this->_tpl_vars['URL']; ?>
upload/order/<?php echo $this->_tpl_vars['key']; ?>
"><?php echo $this->_tpl_vars['URL']; ?>
upload/order/<?php echo $this->_tpl_vars['key']; ?>
</a></td>
</tr>
<?php endforeach; endif; unset($_from); ?>
<?php endif; ?>
 <tr>
        <td height="20" align="left" valign="top" style="text-align:justify">&nbsp;</td>
      </tr>
    </table>
   </td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td height="10" bgcolor="#3d3d3d">&nbsp;</td>
    <td width="500" height="10" align="left" valign="top" bgcolor="#3d3d3d">&nbsp;</td>
    <td height="10" bgcolor="#3d3d3d">&nbsp;</td>
  </tr>
  <tr>
    <td height="70" bgcolor="#2c2c2c">&nbsp;</td>
    <td width="500" height="70" align="center" valign="middle" bgcolor="#2c2c2c">
    <font color="#b1b1b1">Dziękujemy za zakupy w naszym sklepie</font></td>
    <td height="70" bgcolor="#2c2c2c">&nbsp;</td>
  </tr>
</table>
</body>
</html>