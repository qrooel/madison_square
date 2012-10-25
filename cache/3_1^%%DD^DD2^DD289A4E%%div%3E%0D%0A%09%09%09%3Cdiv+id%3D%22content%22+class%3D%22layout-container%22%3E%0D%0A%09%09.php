<?php /* Smarty version 2.6.19, created on 2012-10-16 22:36:24
         compiled from text:%0D%0A%09%09%3C%21DOCTYPE+html+PUBLIC+%22-//W3C//DTD+XHTML+1.1//EN%22+%22http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd%22%3E%0D%0A%09%09%3Chtml+xmlns%3D%22http://www.w3.org/1999/xhtml%22+xml:lang%3D%22pl%22%3E%0D%0A%09%09%3Chead%3E%0D%0A%09%09%09%3Ctitle%3E%7B%24SHOP_NAME%7D+Admin%3C/title%3E%0D%0A%09%09%09%3Cmeta+http-equiv%3D%22Content-Type%22+content%3D%22text/html%3B+charset%3Dutf-8%22/%3E%0D%0A%09%09%09%3Cmeta+http-equiv%3D%22Author%22+content%3D%22Gekosale%3B+http://www.gekosale.pl%22/%3E%0D%0A%09%09%09%3Cmeta+http-equiv%3D%22Description%22+content%3D%22Panel+administracyjny+systemu+sklepowego+Gekosale.%22/%3E%0D%0A%09%09%09%3Cmeta+name%3D%22robots%22+content%3D%22noindex%2C+nofollow%22/%3E%0D%0A%09%09%09%3Clink+rel%3D%22shortcut+icon%22+href%3D%22http://ac.vipserv.org/madison_square2/design/_images_panel/icons/favicon.ico%22/%3E%0D%0A%09%09%09%3Clink+rel%3D%22stylesheet%22+href%3D%22http://ac.vipserv.org/madison_square2/design/_css_panel/core/style.css%3Fv%3D1.4.1%22+type%3D%22text/css%22/%3E%0D%0A%09%09%09%3Clink+rel%3D%22stylesheet%22+href%3D%22http://ac.vipserv.org/madison_square2/design/_css_panel/core/wide.css%3Fv%3D1.4.1%22+type%3D%22text/css%22/%3E%0D%0A%09%09%09%3Clink+rel%3D%22stylesheet%22+href%3D%22http://ac.vipserv.org/madison_square2/design/_js_libs/daterangepicker/css/ui.daterangepicker.css%3Fv%3D1.4.1%22+type%3D%22text/css%22/%3E%0D%0A%09%09%09%3Clink+rel%3D%22stylesheet%22+href%3D%22http://ac.vipserv.org/madison_square2/design/_js_libs/daterangepicker/css/redmond/jquery-ui-1.7.1.custom.css%3Fv%3D1.4.1%22+type%3D%22text/css%22/%3E%0D%0A%09%09%09%3Cscript+type%3D%22text/javascript%22+src%3D%22http://ac.vipserv.org/madison_square2/design/_js_libs/jquery-1.4.2.min.js%3Fv%3D1.4.1%22%3E%3C/script%3E%0D%0A%09%09%09%3Cscript+type%3D%22text/javascript%22+src%3D%22http://ac.vipserv.org/madison_square2/design/_js_libs/ckeditor/ckeditor.js%3Fv%3D1.4.1%22%3E%3C/script%3E%0D%0A%09%09%09%3Cscript+type%3D%22text/javascript%22+src%3D%22http://ac.vipserv.org/madison_square2/design/_js_libs/xajax/xajax_core.js%3Fv%3D1.4.1%22%3E%3C/script%3E%0D%0A%09%09%09%3Cscript+type%3D%22text/javascript%22+src%3D%22http://ac.vipserv.org/madison_square2/design/_js_libs/jquery-ui-1.7.2.custom.min.js%3Fv%3D1.4.1%22%3E%3C/script%3E%0D%0A%09%09%09%3Cscript+type%3D%22text/javascript%22+src%3D%22http://ac.vipserv.org/madison_square2/design/_js_libs/jquery.dimensions.js%3Fv%3D1.4.1%22%3E%3C/script%3E%0D%0A%09%09%09%3Cscript+type%3D%22text/javascript%22+src%3D%22http://ac.vipserv.org/madison_square2/design/_js_libs/jquery.gradient.js%3Fv%3D1.4.1%22%3E%3C/script%3E%0D%0A%09%09%09%3Cscript+type%3D%22text/javascript%22+src%3D%22http://ac.vipserv.org/madison_square2/design/_js_libs/jquery.checkboxes.pack.js%3Fv%3D1.4.1%22%3E%3C/script%3E%0D%0A%09%09%09%3Cscript+type%3D%22text/javascript%22+src%3D%22http://ac.vipserv.org/madison_square2/design/_js_libs/jquery.resize.js%3Fv%3D1.4.1%22%3E%3C/script%3E%0D%0A%09%09%09%3Cscript+type%3D%22text/javascript%22+src%3D%22http://ac.vipserv.org/madison_square2/design/_js_libs/swfobject.js%3Fv%3D1.4.1%22%3E%3C/script%3E%0D%0A%09%09%09%3Cscript+type%3D%22text/javascript%22+src%3D%22http://ac.vipserv.org/madison_square2/design/_js_libs/jquery.swfobject.js%3Fv%3D1.4.1%22%3E%3C/script%3E%0D%0A%09%09%09%3Cscript+type%3D%22text/javascript%22+src%3D%22http://ac.vipserv.org/madison_square2/design/_js_libs/colorpicker.js%3Fv%3D1.4.1%22%3E%3C/script%3E%0D%0A%09%09%09%3Cscript+type%3D%22text/javascript%22+src%3D%22http://ac.vipserv.org/madison_square2/design/_js_libs/swfupload.js%3Fv%3D1.4.1%22%3E%3C/script%3E%0D%0A%09%09%09%3Cscript+type%3D%22text/javascript%22+src%3D%22http://ac.vipserv.org/madison_square2/design/_js_libs/swfupload.queue.js%3Fv%3D1.4.1%22%3E%3C/script%3E%0D%0A%09%09%09%3Cscript+type%3D%22text/javascript%22+src%3D%22http://ac.vipserv.org/madison_square2/design/_js_libs/jquery.swfupload.js%3Fv%3D1.4.1%22%3E%3C/script%3E%0D%0A%09%09%09%3Cscript+type%3D%22text/javascript%22+src%3D%22http://ac.vipserv.org/madison_square2/design/_js_libs/json2.js%3Fv%3D1.4.1%22%3E%3C/script%3E%0D%0A%09%09%09%3Cscript+type%3D%22text/javascript%22+src%3D%22http://ac.vipserv.org/madison_square2/design/_js_libs/base64.js%3Fv%3D1.4.1%22%3E%3C/script%3E%0D%0A%09%09%09%3Cscript+type%3D%22text/javascript%22+src%3D%22http://ac.vipserv.org/madison_square2/design/_js_libs/jquery.onkeyup.js%3Fv%3D1.4.1%22%3E%3C/script%3E%0D%0A%09%09%09%3Cscript+type%3D%22text/javascript%22+src%3D%22http://ac.vipserv.org/madison_square2/design/_js_libs/daterangepicker/js/daterangepicker.jQuery.js%3Fv%3D1.4.1%22%3E%3C/script%3E%0D%0A%09%09%09%3Cscript+type%3D%22text/javascript%22+src%3D%22http://ac.vipserv.org/madison_square2/design/_js_panel/core/gekosale.js%3Fv%3D1.4.1%22%3E%3C/script%3E%0D%0A%09%09%09%3Cscript+type%3D%22text/javascript%22%3E%0D%0A%09%09%09%09%7Bliteral%7D%0D%0A%09%09%09%09%09/%2A%3C%21%5BCDATA%5B%2A/%0D%0A%09%09%09%09%09%09new+GCore%28%7B%0D%0A%09%09%09%09%09%09%09iCookieLifetime:+30%2C%0D%0A%09%09%09%09%09%09%09sDesignPath:+%27%7B/literal%7Dhttp://ac.vipserv.org/madison_square2/design/%7Bliteral%7D%27%2C%0D%0A%09%09%09%09%09%09%09iActiveView:+%27%7B/literal%7D%7B%24view%7D%7Bliteral%7D%27%2C%0D%0A%09%09%09%09%09%09%09aoViews:+%7B/literal%7D%7B%24views%7D%7Bliteral%7D%2C%0D%0A%09%09%09%09%09%09%09iActiveLanguage:+%27%7B/literal%7D%7B%24language%7D%7Bliteral%7D%27%2C%0D%0A%09%09%09%09%09%09%09aoLanguages:+%7B/literal%7D%7B%24languages%7D%7Bliteral%7D%2C%0D%0A%09%09%09%09%09%09%09sUrl:+%27%7B/literal%7D%7B%24URL%7D%7Bliteral%7D%27%2C%0D%0A%09%09%09%09%09%09%09sCurrentController:+%27%7B/literal%7D%7B%24CURRENT_CONTROLLER%7D%7Bliteral%7D%27%2C%0D%0A%09%09%09%09%09%09%09sCurrentAction:+%27%7B/literal%7D%7B%24CURRENT_ACTION%7D%7Bliteral%7D%27%2C%0D%0A%09%09%09%09%09%09%7D%29%3B%0D%0A%09%09%09%09%09%09%24%28document%29.ready%28function%28%29%7B%0D%0A%09%09%09%09%09%09%09%24%28%27%23search%27%29.GSearch%28%29%3B+%0D%0A%09%09%09%09%09%09%7D%29%3B%0D%0A%09%09%09%09%09/%2A%5D%5D%3E%2A/%0D%0A%09%09%09%09%7B/literal%7D%0D%0A%09%09%09%3C/script%3E%0D%0A%09%09%09%3Cscript+type%3D%22text/javascript%22+src%3D%22http://ac.vipserv.org/madison_square2/design/_js_panel/core/init.js%22%3E%3C/script%3E%0D%0A%09%09%09%3Cscript+type%3D%22text/javascript%22+src%3D%22http://ac.vipserv.org/madison_square2/design/_js_panel/core/gf.js%22%3E%3C/script%3E%0D%0A%09%09%09%3Cscript+type%3D%22text/javascript%22+src%3D%22http://ac.vipserv.org/madison_square2/design/_js_panel/core/pl_PL.js%22%3E%3C/script%3E%0D%0A%09%09%09%0D%0A%09%09%09%3Cscript+type%3D%22text/javascript%22%3E%0D%0A%09%09%09%09GF_Debug.s_iLevel+%3D+GF_Debug.LEVEL_ALL%3B%0D%0A%09%09%09%3C/script%3E%0D%0A%09%09%09%0D%0A%09%09%3C%21--+end:+GexoFramework+--%3E%0D%0A%09%09%0D%0A%09%09%7B%24xajax%7D%0D%0A%09%09%0D%0A%09%09%7Bif+isset%28%24error%29%7D%0D%0A%09%09%09%3Cscript+type%3D%22text/javascript%22%3E%0D%0A%09%09%09%09%7Bliteral%7D%0D%0A%09%09%09%09%09%24%28document%29.ready%28function%28%29%7B%0D%0A%09%09%09%09%09%09GError%28%27%7B/literal%7D%7Btrans%7DTXT_ERROR_OCCURED%7B/trans%7D%7Bliteral%7D%27%2C+%27%7B/literal%7D%7B%24error%7D%7Bliteral%7D%27%29%3B%0D%0A%09%09%09%09%09%7D%29%3B%0D%0A%09%09%09%09%7B/literal%7D%0D%0A%09%09%09%3C/script%3E%0D%0A%09%09%7B/if%7D%0D%0A%09%3C/head%3E%0D%0A%09%3Cbody%3E%0D%0A%0D%0A%09%09%3C%21--+begin:+Header+--%3E%0D%0A%09%09%09%3Cdiv+id%3D%22header%22%3E%0D%0A%0D%0A%09%09%09%09%3Cdiv+class%3D%22layout-container%22%3E%0D%0A%0D%0A%09%09%09%09%09%3Ch1%3E%3Ca+href%3D%22%7B%24URL%7Dmainside%22+accesskey%3D%220%22+title%3D%22%7Btrans%7DTXT_RETURN_TO_DESKTOP%7B/trans%7D%22%3E%3Cimg+src%3D%22data:image/png%3Bbase64%2CiVBORw0KGgoAAAANSUhEUgAAALYAAAAcCAYAAADMd0WMAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAErJJREFUeNrsXAtwVEW6/ueV5wBZBBJIwjMQFgWCPBYUJdQqG8AHuuUCQgl6EVSsy/MiwlLA3oKrldVc6u4KC2oSEcUXD2UvAYSABlwFkwiIECMGE8iEEDJJJsm8z/3%2BQ5/QHCfDRMGy6k5Tf50zffr/u0/3139/f/cJBiIy0JWk0C%2BcFOUXrzKc/p8kBrVRAvaPkKZQ759Zwdkw4MPpF09mAexAgI7CxUfKd%2B6f5ZVDAb/BEB6JcLqhySjAbRL3Bk1cpExrIn9/Qwgp3I3h9Gv02BGqZ74ifo2S%2BAyGByF9kHMqiCPmqwnYjsS1PSQaYhGTg%2B25IA2QRohbCXOMcPoFgR0pQOgVwFbB7Vb8fUxk%2BUMJJR/uR%2BX7AwCbPXwMpMvDFDuuH1nu6UbmQUC0JYYM7ZtIsTtJqSkjzydHyPlREblPYALUhAEeTr9U8NgT4tF77fPU40gMRaV6DT5XveLaXE3%2Bg5fIVxlPpkQgOh4Kvb2kDIGBW61kbOcG1lmqYaJONXF1uwVGvWfIs3sHNWaeJs9xZNXL4NazmTDuw%2BlGALv/VfxdBXYhJa3uTOYZVrIiww/ke%2BDSvSDjJqFogMs2XmPMh39cphnXEpS/iKsM2UZ48PfIMe8QOXfjZ60G7jCww%2BlmAHuA4MXXoBT0In4Ndfwgkswx7VXGEXqM6AWgm8lJx2MUgoemjk2ulmceUprfo8Y5%2BeT8UPPcYWCH083YFfGKe5MIJDkAjNlGjY0fUuN/1ZHHW0UORIAuUA32yP7rCk%2BCaPwb3GShPqYo2tsnXq3Ee8XTR0%2Bk2Ky%2BZBkk6rv%2B7APwZUGaBNlOV/feWYogWUytdGVDSTMh2ZB8yMpf2Rj1FG3KF%2B%2BcdhPryhb1hZrSRZ/nC924m/DucfrxD0WMYsfCJWiIIlwzg9z8HF3%2BbDM1LKsmd8VFFCsHez5HdvVaCajbAPha1Td7VC%2BtFzPMD2kw0BCHhz5K7Yo8RRVEq7eMp%2BilqKNTW7YL4cnjIDy42ysrK2%2BZNGnSJqgfZOnSpYtj1apVt7tcrlPffffdvDZ2YM7tt9%2B%2BC7q/w/2qjIyMRb8iYJfh/f6xd%2B9eO0/ogoKCHPxOuUkTaOa2bdu4TxNC1OG%2Bzy0rK0tk3XXr1m3Eb%2BsNag9P4O99Ph/HZNaf4rGbIHViW44B7hbOVeXbL1NdYQbZZm0mR%2BZxclfXwCM3QupQxA5AVwHWZQB4tQpvfwt4NTGhiokX3WQwm%2BjjlKueuztZxg2miMGhem3Jo0x65513PunWrZtv586dVfj9JGRsdXX1XatXr564fPnyxfHx8ZlDhgz5Y1s6orCw8AOTyXSM76Oioqa3YXBvesJkto0bN24d30dHR/fA5Y83oRp1pRo1atRoXP7QhrYV9%2BzZc4tYWYfjMvpGTWi/31/f3NzcFfebfgqwXQLc7BEuC5CzF2%2BGOPm5kxTX36j%2BVD41R4CiEMv2Dkb6OtqIAooqVTBTDjOeANQkRjHTMDuex8VSVUwE82zkKuZBFPFwqLMRHch0YdL58%2BerpkyZwnwnB7ICUioVc7z00kuvAOTre/XqtaitM91sNvuknwn06003um1MIdLtdntt165do5YtW/b0z7AVf4PaZN%2B4ceOAkSNHfvJTPLZZbPX5hSP1CJBzMBkpvClLu15k7m4lYwcuaDcZ6Ei8lf6JIC/B5aNpVU3U1eUHwL0qzONV1WtTsmDy3yR0oBFnq9T7W8g4im1Daq7XUCxJKr0AcGsFmHNbK5ucnJzlcDjYq6VAr1jHVTUOuVNMDnuASaTVKXNw9pSHePmV%2BOWMEOzNhzwo/Q5Uju2PkWyVQdhDF%2BvbBi%2BmbxuDcp5oDwmddcJGyDEG%2BuutrVu3PvDkk0/%2BBhQvFd43w%2Bv15rUyCVZKXH%2Bn1m5u29KlS/ujbVqcck6860zRB3GiXavFNVh%2BD7SFsFL9tU%2BfPkVoy0pdH9rFe8vtyIFzsmse2ycA7RYemoHt4O04SDXExteuZIqJAv1mlDdFWcgoSLotykRZye3o83Zm1XPbYKY5QJB5yXJl06XaGq16bBbYSxbBKoUA7DQezN27d3PDP7iO5y0DJ%2BXl0cY6QrcIy9rwZ599tmTx4sUNGMgX4NmL1q9fnyjKtIgMbEg2TyroVMNuDwjbmgnJr6io6Mj21qxZ427FXj4P8pYtW75%2B/PHHL7Fs3rx5cl1dXQnsWEWZlZAscNvLUtumNjU1HcnOzr5f3zYN0OI%2BjuvAoC94%2BeWXj7P9c%2BfOTYPuKfTT7wPptiLzpk%2BfXoE25PDvwYMHxw0dOvSRAOW4vu9R3%2BOvvPJKBdeHdx%2BP95mo9dsLL7xw%2Bt577z1QVVX1BPddQ0MDj0GPzMzMzyAN3Hc1NTWFtbW1WwPlo18ScJ/D/Y3rqqSkpP8dO3ZsX%2BSvfv3115M4D%2BN4uL6%2BfuvJkye/Wrhw4VcHDhzown2I1bxA9u7a9yFGuvrtiEV46igBvK4TKXrDf1AHZTFkSsck5daBg5T%2Bvx2g9O2XqqT066ekpvRT/jO6q5JLnZX9lKgUU89rZMpvhyi3DxuujBgyVFlOcaospQ48kW4L5DFlaWxsTIcoLCICD3kphg5LETq4rnPnznmCr6WBn69hexs2bPiSO0OUy%2Be8%2B%2B67j3dY0nCfBant0aPHKqGXorUFAdNXoi3PcVkM8BLOP3bs2AVhj/WV11577TP8/giSIbxL2v79%2B/ekp6fnaXWiDZvkMm%2B88cbzrLtnz55KUWdLHyB4ZIeTJfL4qkyePHmX0B/9zDPPTOC8I0eOXJJ0g8kkCMcub3O/Xrx4cTfrb9q0qZx/68pm87MFCxYUy%2B3Nz8/fzPkA6Pe8inFZAHuNZOdtsbqlnT59%2Bgjn4x1Pi767Jh9e%2BlOtPrTlX9J4WMV4qjgQY5klOH3a2bNnz3P%2BtGnT3tOoiH7T2CdtWmtXj58M6ld%2B2lm6xWIhBFvk9XrI4/GQz6jQnk7R9Fh5A6JQ5Zp9n8vg4iXR4mBHueKtY2HF1cqnsvrE9nXJFpSc2e0zxTJ1ELpMH9KOHj16Dh6VDS3Ac16R7Hi2bNiwYf04GMN9rrzcv/vuu2ORN3/27NkvwAsORfZU1kPeci6DIJW7oRh5L2oUAJ7riZSUlFQErutQ7n84E97vtr59%2By5B/fKyPgce5rHY2NgU6I996qmneID%2BjnutTBn012JCcTfOwP2KljMCr1fuk5kul6sZwXSseC%2BVujidzvIBAwYkgy8vRLlnrtO98zD5tvEWL/S5X/8BnYwJEyYkIE6Zjvu/SmV5m5WysrJqde0tVTEgVhO%2Bj4yMdPMVXpgbXICyWv/uQf6oCxcuMF916PNBO5LQL6ORXxAREdHMNgT94p2gYg0LGMtIEWPxWFLHjh034tmqgQMHDuPxlg9lWvaEkbSPobRvR5wO8pfHAOd%2BphBuz5W9QqORIiIiVblCS8x0IdJIboOfPMar8n48HL/xyhyJdHtVJt4FwK4hX62gQMEPfLzeYh5QbVCDpUuXLqWjXHZJSUl1XFycFQCdznoAFr9PMZ47hK0yviKiZ08wWrPPMmjQIOuYMWNWg86shf5IBo2kx/YpLy9PpUSyHgZiN19HjRp1B%2Bq2wYN8w/Z37dr1IvTn4FmcVvenn366kQeFf%2BMZ288Tz9l%2BGud36tSJV8wUuQ4NPKJM3KlTp1TqCP2WPsL4fcfXtLS0UdwHsr5OerIsWrSIP7rfI/J2gArZAEwzPOV0SV%2Btr6ioqEb0c57ensb/A%2BQ5WinbWn4fOU8%2BsNPhwKHPF/qDjUF2IRTpew5XKXm%2BVDgbP2IdTRTp8bVshrP3ZmH/XmXhg3ZDy3ZfncVAR38TdTXyqHPwR94UYzDSZfKVCE4fNGGA7fBMP/BsHTFihPU63n0Ml8My3pc9yf3333%2BCf4MP9qysrPwz7hVJNM%2BXot2zTJkyJQkd1O7QoUMT8exF6JVKz3tKnqhY1oPwKkC8s6B6DaPxDtCOkvbt27eDF9oA7lsBSvQ3lImDTRsL30OyIbWQ78Ef/wLbmVLbEuQ6JI/NetS/f/9bYOc%2B3Xul8zMxfCm6NsqyEp5v3wMPPDAINuKEXjpW4jzRD3140ouyan1ut9sU4L1J77GD5bU1Xwa21C8/WtVlfXMI22x85O2tJN/3ZQbvtykU0e%2BC4qEu1bVUkdhZrfQKuCPE7PJTO6NJ3fbjtBv0BJxFvbd4vBRfWUM9DBF0HvCuJP/HYv88lODxdQ4csLRbv/jiixTdNt%2BPaIuYuQwcNWJHIFa2cuXKslDoDgK8ClCQ7gjmbkV%2Bb11H2rUTth9%2B%2BMERqJOxjDL64hno4L9Dhw8f/udHH310WkZGRgIAPtdmsz2I4G4kArbzgqen4ffHc%2BbMcQoTX8L2sEBt01EROnHihH3ixInFbaRyLVt88Lz%2BpUuX%2Bi5fvpwpP4THbk5OTrbefffds6Gf11r9gXZs5GeB8tqar%2BUFeRf9uF8f2BK4a75R3K/1NkaonLJz5SVqiI0iewdry7F3jE%2BhVLdCTiOpOyScGiKuVpH0QxVCSwMlmiLoI2%2B97Si5dobisUWHMm9dwt4UAUmrwNY6XlqyD6luKyWFG5qDICM3mB6n48ePOwC0TfBkc2fNmpWJgOZf69evL9RO25hrIvKPC0CN1C03eHo7eDXbjEN9PBGW9u7d%2B7/BTafn5uauAHdOgmd%2BH9z9L4mJiWmI5msAau6o97X2QXd%2BoLZJ76VuO8IWv1cx9BYE6buAW3xYBQ/fddddpkAxy5YtW2JAqQYgGBvVvXv3FNiX6wtoU2pb0Ly25uupSCjjbmzDXmfjUXJv%2B0ZxnUkAMHledC8tp3b1jVcM%2BRW646IDwDWpxEeTYdUNFO/yUY9zlTTA3kwDTNFU5Gvij6PWuUgpR6O9oVQOYNjhXf4tKSnJisBtZTDvpFvSDoLrXkAwFQeKcFuAJXSmPo9n/fz5818tLS3d16FDhxjQmJ2o3yqe7%2BTrQw89lIC8NB1FScfSXn/s2DEHeKsXlGKypnfmzBnbvn37OBCbIupIwGThFYFAUTgQKkWZXH1b9Muy7DEhOaCA5nHjxv0ulPfSyby5c%2Bce5kmBehfoBUH1REH9OmHyTRc6O4LUd9OoiN5jh0JFQgY2AMgaFwoU57xKxdeYYEHAiOCxV2kF9YX8vrSSBjf7qNmoUBOCR03MCDQfPnOBJtR5qTt0ykFSjiquHcfI/VaoNERLd95551Z4w7UA1bAPP/wwH4HTNR/s4DfvMafzy8ELOrUZDM44l4Mh0JHZoDEDxJ5sT7FHbU9NTc0ItI%2BNSfSnqqqqCwgmk958880C8Zy9/r709PSE559/fpm0v7sdHVv/9NNPc9xgwxL%2Bz6ioqMzFixc/I%2B8Dx8fH/wk8teHtt9%2B2vfXWW9VOp/M8L/mwdZtkS22Xbs860D72anj%2B5rVr1w7fsWPHLEl/PmSw/r0k4T4qO3DgwN180NVKmTKk%2BdxvcCRPwBbvu6/jZ5j0A2fMmHGXZGtmgP1/ai2vrfmyx5b7QO/p5bJt8dicmsGNPz/oc8ytVXyu/pYY6hURRSOcCiV6FYAabj2InAc3P%2BhrPLSfnEt48MVkaVMC910Objrl888/7wauehi0QdEE3iwLQVDZI4888npOTg4vr1VapA8Q/TuAZwRH/xq/uWx2YWHhR/DkqVJ0Xvztt99W1NbWesXyZt%2B7d%2B%2BIgoKCElCZW1Hfed5FgAcbl5eX9/E999xzv7BVC3wxL98Pm1zvCtbFCjMbHm%2BJKKOV67Rw4cJsTA7eZ94DCncfJuTFqVOnjhRl8jGZ9oCy7Dt58mSNFPGDutuPlZSUOKS8su3bt48Bz740fvz4TUK/CHy5Ge91WL%2B7IMkYTAT1AAv9Zmtt1wQr3LpXX331OfSbBRP7INeXn5%2B/kMG%2BcePGTziGQN6DWNEewypVgeSU2nYOzuW0Lq%2Bt%2BdeMh0bB0GZ7ACpyjb6hFe8c7BNSngztu5BpxGhz7Ib%2BRkuvdsbgVN0HexVet/%2BE4so97G9eg6wK1OGSbAatv5UPAK3iY6DRYo%2BTpCPlUnE6aQvwxdhcqXyxOJqXgy/t%2Bd8lHm8V%2BaOFzRXiOkMcUmgHRgXCnsz/R4t2yp%2Bb6sulCPtaGe2TAav4IGmBrlypaJ/87chc6QMkm9DPCzIs3PavAh3bt/KlnfZhVK7UjgTBOPNEPh%2B4HJbqzRB6e3RtaUt%2BoPHIEld9XNGi32ZgC6AZxIlk4kCyTO5tjprVmYzdow1GQ7TRqP51jcvvI5fiJ4fid5b7PQVfK%2B7MavJzR16GfU8w4Ib/0CCcfm76ScCWAGkSXuWWDmRM7kqmIVYypELbCI5RW0/%2BU2fJWyg%2BcuLDGFegP%2BQNAzucbjiwbwSIhAfX/gInkq797xfc4iQzmH4Y2OF0Q9P/CTAAuGY58ECoyDgAAAAASUVORK5CYII%3D%22+/%3E%3C/a%3E%3C/h1%3E+%0D%0A%0D%0A%09%09%09%09%09%09%3Cdiv+id%3D%22appversion%22%3E%0D%0A%09%09%09%09%09%09%09%3Ch3%3E%7Btrans%7DTXT_VERSION%7B/trans%7D:+%7B%24appversion%7D%3C/h3%3E%0D%0A%09%09%09%09%09%09%3C/div%3E%0D%0A%09%09%09%09%09%3C%21--+begin:+Quick+Access+--%3E%0D%0A%09%09%09%09%09%09%3Cdiv+id%3D%22quick-access%22%3E%0D%0A%09%09%09%09%09%09%09%3Ch3%3E%7Btrans%7DTXT_QUICK_ACCESS%7B/trans%7D:%3C/h3%3E%0D%0A%09%09%09%09%09%09%09%3Cul%3E%0D%0A%09%09%09%09%09%09%09%09%3Cli%3E%3Ca+href%3D%22%7B%24URL%7Dproducts/add%22%3E%7Btrans%7DTXT_ADD_PRODUCT%7B/trans%7D%3C/a%3E%3C/li%3E%0D%0A%09%09%09%09%09%09%09%3C/ul%3E%0D%0A%09%09%09%09%09%09%3C/div%3E%0D%0A%0D%0A%09%09%09%09%09%09%3Cdiv+id%3D%22livesearch%22%3E%0D%0A%09%09%09%09%09%09%09%3Ch3%3E%7Btrans%7DTXT_SEARCH%7B/trans%7D:+%3Cinput+type%3D%22text%22+name%3D%22search%22+id%3D%22search%22+/%3E%3C/h3%3E%0D%0A%09%09%09%09%09%09%3C/div%3E%0D%0A%09%09%09%09%09%09%0D%0A%09%09%09%09%09%09%3Cscript+type%3D%22text/javascript%22%3E%0D%0A%09%09%09%09%09%09%09%7Bliteral%7D%0D%0A%09%09%09%09%09%09%09%09/%2A%3C%21%5BCDATA%5B%2A/%0D%0A%09%09%09%09%09%09%09%09%09var+aoQuickAccessPossibilites+%3D+%5B%0D%0A%09%09%09%09%09%09%09%09%09%09%7BmLink:+%27%7B/literal%7D%7B%24URL%7D%7Bliteral%7Dorder%27%2C+sCaption:+%27%7B/literal%7D%7Btrans%7DTXT_ORDER_LIST%7B/trans%7D%7Bliteral%7D%27%2C+bDefault:+true%7D%2C%0D%0A%09%09%09%09%09%09%09%09%09%09%7BmLink:+%27%7B/literal%7D%7B%24URL%7D%7Bliteral%7Dproduct/add%27%2C+sCaption:+%27%7B/literal%7D%7Btrans%7DTXT_ADD_PRODUCT%7B/trans%7D%7Bliteral%7D%27%2C+bDefault:+true%7D%2C%0D%0A%09%09%09%09%09%09%09%09%09%09%7BmLink:+%27%7B/literal%7D%7B%24URL%7D%7Bliteral%7Dproduct%27%2C+sCaption:+%27%7B/literal%7D%7Btrans%7DTXT_PRODUCT_LIST%7B/trans%7D%7Bliteral%7D%27%2C+bDefault:+true%7D%2C%0D%0A%09%09%09%09%09%09%09%09%09%09%7BmLink:+%27%7B/literal%7D%7B%24URL%7D%7Bliteral%7Dcategory/add%27%2C+sCaption:+%27%7B/literal%7D%7Btrans%7DTXT_ADD_CATEGORY%7B/trans%7D%7Bliteral%7D%27%7D%2C%0D%0A%09%09%09%09%09%09%09%09%09%09%7BmLink:+%27%7B/literal%7D%7B%24URL%7D%7Bliteral%7Dclient/add%27%2C+sCaption:+%27%7B/literal%7D%7Btrans%7DTXT_ADD_CLIENT%7B/trans%7D%7Bliteral%7D%27%7D%2C%0D%0A%09%09%09%09%09%09%09%09%09%09%7BmLink:+%27%7B/literal%7D%7B%24URL%7D%7Bliteral%7Dcategory%27%2C+sCaption:+%27%7B/literal%7D%7Btrans%7DTXT_CATEGORY_LIST%7B/trans%7D%7Bliteral%7D%27%7D%2C%0D%0A%09%09%09%09%09%09%09%09%09%09%7BmLink:+%27%7B/literal%7D%7B%24URL%7D%7Bliteral%7Dclient%27%2C+sCaption:+%27%7B/literal%7D%7Btrans%7DTXT_CLIENT_LIST%7B/trans%7D%7Bliteral%7D%27%7D%2C%0D%0A%09%09%09%09%09%09%09%09%09%09%7BmLink:+%27%7B/literal%7D%7B%24URL%7D%7Bliteral%7Dstatssales%27%2C+sCaption:+%27%7B/literal%7D%7Btrans%7DTXT_STATSSALES%7B/trans%7D%7Bliteral%7D%27%2C+bDefault:+true%7D%2C%0D%0A%09%09%09%09%09%09%09%09%09%09%7BmLink:+%27%7B/literal%7D%7B%24URL%7D%7Bliteral%7Dstatsclients%27%2C+sCaption:+%27%7B/literal%7D%7Btrans%7DTXT_STATSCLIENTS%7B/trans%7D%7Bliteral%7D%27%2C+bDefault:+true%7D%2C%0D%0A%09%09%09%09%09%09%09%09%09%09%7BmLink:+%27%7B/literal%7D%7B%24URL%7D%7Bliteral%7Dstatsproducts%27%2C+sCaption:+%27%7B/literal%7D%7Btrans%7DTXT_STATSPRODUCTS%7B/trans%7D%7Bliteral%7D%27%2C+bDefault:+true%7D%2C%0D%0A%09%09%09%09%09%09%09%09%09%09%7BmLink:+%27%7B/literal%7D%7B%24URL%7D%7Bliteral%7Dproductpromotion%27%2C+sCaption:+%27%7B/literal%7D%7Btrans%7DTXT_PROMOTIONS_LIST%7B/trans%7D%7Bliteral%7D%27%7D%0D%0A%09%09%09%09%09%09%09%09%09%5D%3B%0D%0A%09%09%09%09%09%09%09%09/%2A%5D%5D%3E%2A/%0D%0A%09%09%09%09%09%09%09%7B/literal%7D%0D%0A%09%09%09%09%09%09%3C/script%3E%0D%0A%09%09%09%09%09%09%3Cdiv+id%3D%22top-menu%22%3E%0D%0A%09%09%09%09%09%09%09%3Cul%3E%0D%0A%09%09%09%09%09%09%09%09%3Cli%3E%0D%0A%09%09%09%09%09%09%09%09%09%3Ca+href%3D%22%7B%24URL%7Dusers/edit/%7B%24user_id%7D%22+class%3D%22icon-person%22%3E%3Cstrong%3E%7B%24user_name%7D%3C/strong%3E%3C/a%3E%0D%0A%09%09%09%09%09%09%09%09%3C/li%3E%0D%0A%09%09%09%09%09%09%09%09%3Cli%3E%0D%0A%09%09%09%09%09%09%09%09%09%3Ca+href%3D%22%7B%24URL%7Dlogout%3B%22+class%3D%22icon-logout%22%3E%7Btrans%7DTXT_LOGOUT%7B/trans%7D%3C/a%3E%0D%0A%09%09%09%09%09%09%09%09%3C/li%3E%0D%0A%09%09%09%09%09%09%09%09%3Cli%3E%0D%0A%09%09%09%09%09%09%09%09%09%3Ca+href%3D%22%7B%24FRONTEND_URL%7D%22+target%3D%22_blank%22+%3E%7Btrans%7DTXT_HOME_PAGE%7B/trans%7D%3C/a%3E%0D%0A%09%09%09%09%09%09%09%09%3C/li%3E%0D%0A%09%09%09%09%09%09%09%3C/ul%3E%0D%0A%09%09%09%09%09%09%3C/div%3E%0D%0A%09%09%09%09%3C/div%3E%0D%0A%09%09%09%3C/div%3E%0D%0A%09%09%09%3Cdiv+id%3D%22navigation-bar%22%3E%0D%0A%09%09%09%09%3Cdiv+class%3D%22layout-container%22%3E%0D%0A%09%09%09%09%0D%0A%09%09%09%09%09%3Cdiv+id%3D%22selectors%22+style%3D%22float:+right%3B+margin-top:+8px%3B%22%3E%3C/div%3E%0D%0A%09%09%09%09%09%0D%0A%09%09%09%09%09%3Cul+id%3D%22navigation%22%3E%0D%0A%09%09%09%09%09%09%7Bif+isset%28%24menu%29%7D%0D%0A%09%09%09%09%09%09%09%7Bsection+name%3Dblock+loop%3D%24menu%7D%0D%0A%09%09%09%09%09%09%09%09%3Cli%3E%0D%0A%09%09%09%09%09%09%09%09%09%7Bif+isset%28%24menu%5Bblock%5D.elements%29%7D%0D%0A%09%09%09%09%09%09%09%09%09%09%3Ca+href%3D%22%7B%24URL%7D%7B%24menu%5Bblock%5D.elements%5B0%5D.link%7D%22%3E%7Btrans%7D%7B%24menu%5Bblock%5D.name%7D%7B/trans%7D%3C/a%3E%0D%0A%09%09%09%09%09%09%09%09%09%7Belse%7D%0D%0A%09%09%09%09%09%09%09%09%09%09%7Bif+%24menu%5Bblock%5D.link+%3D%3D+%27mainside%27%7D%0D%0A%09%09%09%09%09%09%09%09%09%09%09%3Ca+href%3D%22%7B%24URL%7D%7B%24menu%5Bblock%5D.link%7D%22%3E%7Btrans%7D%7B%24menu%5Bblock%5D.name%7D%7B/trans%7D%3C/a%3E%0D%0A%09%09%09%09%09%09%09%09%09%09%7B/if%7D%0D%0A%09%09%09%09%09%09%09%09%09%7B/if%7D%0D%0A%09%09%09%09%09%09%09%09%09%7Bif+isset%28%24menu%5Bblock%5D.elements%29%7D%0D%0A%09%09%09%09%09%09%09%09%09%09%3Cul%3E%0D%0A%09%09%09%09%09%09%09%09%09%09%09%7Bsection+name%3Delement+loop%3D%24menu%5Bblock%5D.elements%7D%0D%0A%09%09%09%09%09%09%09%09%09%09%09%09%3Cli+%7Bif+%24CURRENT_CONTROLLER+%3D%3D+%24menu%5Bblock%5D.elements%5Belement%5D.link%7Dclass%3D%22active%22%7B/if%7D%3E%0D%0A%09%09%09%09%09%09%09%09%09%09%09%09%09%3Ca+href%3D%22%7B%24URL%7D%7B%24menu%5Bblock%5D.elements%5Belement%5D.link%7D%22%3E%7B%24menu%5Bblock%5D.elements%5Belement%5D.name%7D%3C/a%3E%0D%0A%09%09%09%09%09%09%09%09%09%09%09%09%09%7Bif+isset%28%24menu%5Bblock%5D.elements%5Belement%5D.subelement%29%7D%0D%0A%09%09%09%09%09%09%09%09%09%09%09%09%09%09%3Cul%3E%0D%0A%09%09%09%09%09%09%09%09%09%09%09%09%09%09%09%7Bsection+name%3Dsub+loop%3D%24menu%5Bblock%5D.elements%5Belement%5D.subelement%7D%0D%0A%09%09%09%09%09%09%09%09%09%09%09%09%09%09%09%09%3Cli+%7Bif+%24CURRENT_CONTROLLER+%3D%3D+%24menu%5Bblock%5D.elements%5Belement%5D.subelement%5Bsub%5D.link%7Dclass%3D%22active%22%7B/if%7D%3E%0D%0A%09%09%09%09%09%09%09%09%09%09%09%09%09%09%09%09%09%3Ca+href%3D%22%7B%24URL%7D%7B%24menu%5Bblock%5D.elements%5Belement%5D.subelement%5Bsub%5D.link%7D%22%3E%7B%24menu%5Bblock%5D.elements%5Belement%5D.subelement%5Bsub%5D.name%7D%3C/a%3E%0D%0A%09%09%09%09%09%09%09%09%09%09%09%09%09%09%09%09%3C/li%3E%09%0D%0A%09%09%09%09%09%09%09%09%09%09%09%09%09%09%09%7B/section%7D%0D%0A%09%09%09%09%09%09%09%09%09%09%09%09%09%09%3C/ul%3E%0D%0A%09%09%09%09%09%09%09%09%09%09%09%09%09%7B/if%7D%0D%0A%09%09%09%09%09%09%09%09%09%09%09%09%3C/li%3E%09%0D%0A%09%09%09%09%09%09%09%09%09%09%09%7B/section%7D%0D%0A%09%09%09%09%09%09%09%09%09%09%3C/ul%3E%0D%0A%09%09%09%09%09%09%09%09%09%7B/if%7D%0D%0A%09%09%09%09%09%09%09%09%3C/li%3E%09%0D%0A%09%09%09%09%09%09%09%7B/section%7D%0D%0A%09%09%09%09%09%09%7B/if%7D%0D%0A%09%09%09%09%09%3C/ul%3E%0D%0A%09%09%09%09%3C/div%3E%0D%0A%09%09%09%3C/div%3E%0D%0A%09%09%09%3Cdiv+id%3D%22message-bar%22%3E%0D%0A%09%09%09%09%3Ch2+class%3D%22aural%22%3E%7Btrans%7DTXT_NEWS%7B/trans%7D%3C/h2%3E%0D%0A%09%09%09%3C/div%3E%0D%0A%09%09%09%3Cdiv+id%3D%22content%22+class%3D%22layout-container%22%3E%0D%0A%09%09 */ ?>

		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl">
		<head>
			<title><?php echo $this->_tpl_vars['SHOP_NAME']; ?>
 Admin</title>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
			<meta http-equiv="Author" content="Gekosale; http://www.gekosale.pl"/>
			<meta http-equiv="Description" content="Panel administracyjny systemu sklepowego Gekosale."/>
			<meta name="robots" content="noindex, nofollow"/>
			<link rel="shortcut icon" href="http://ac.vipserv.org/madison_square2/design/_images_panel/icons/favicon.ico"/>
			<link rel="stylesheet" href="http://ac.vipserv.org/madison_square2/design/_css_panel/core/style.css?v=1.4.1" type="text/css"/>
			<link rel="stylesheet" href="http://ac.vipserv.org/madison_square2/design/_css_panel/core/wide.css?v=1.4.1" type="text/css"/>
			<link rel="stylesheet" href="http://ac.vipserv.org/madison_square2/design/_js_libs/daterangepicker/css/ui.daterangepicker.css?v=1.4.1" type="text/css"/>
			<link rel="stylesheet" href="http://ac.vipserv.org/madison_square2/design/_js_libs/daterangepicker/css/redmond/jquery-ui-1.7.1.custom.css?v=1.4.1" type="text/css"/>
			<script type="text/javascript" src="http://ac.vipserv.org/madison_square2/design/_js_libs/jquery-1.4.2.min.js?v=1.4.1"></script>
			<script type="text/javascript" src="http://ac.vipserv.org/madison_square2/design/_js_libs/ckeditor/ckeditor.js?v=1.4.1"></script>
			<script type="text/javascript" src="http://ac.vipserv.org/madison_square2/design/_js_libs/xajax/xajax_core.js?v=1.4.1"></script>
			<script type="text/javascript" src="http://ac.vipserv.org/madison_square2/design/_js_libs/jquery-ui-1.7.2.custom.min.js?v=1.4.1"></script>
			<script type="text/javascript" src="http://ac.vipserv.org/madison_square2/design/_js_libs/jquery.dimensions.js?v=1.4.1"></script>
			<script type="text/javascript" src="http://ac.vipserv.org/madison_square2/design/_js_libs/jquery.gradient.js?v=1.4.1"></script>
			<script type="text/javascript" src="http://ac.vipserv.org/madison_square2/design/_js_libs/jquery.checkboxes.pack.js?v=1.4.1"></script>
			<script type="text/javascript" src="http://ac.vipserv.org/madison_square2/design/_js_libs/jquery.resize.js?v=1.4.1"></script>
			<script type="text/javascript" src="http://ac.vipserv.org/madison_square2/design/_js_libs/swfobject.js?v=1.4.1"></script>
			<script type="text/javascript" src="http://ac.vipserv.org/madison_square2/design/_js_libs/jquery.swfobject.js?v=1.4.1"></script>
			<script type="text/javascript" src="http://ac.vipserv.org/madison_square2/design/_js_libs/colorpicker.js?v=1.4.1"></script>
			<script type="text/javascript" src="http://ac.vipserv.org/madison_square2/design/_js_libs/swfupload.js?v=1.4.1"></script>
			<script type="text/javascript" src="http://ac.vipserv.org/madison_square2/design/_js_libs/swfupload.queue.js?v=1.4.1"></script>
			<script type="text/javascript" src="http://ac.vipserv.org/madison_square2/design/_js_libs/jquery.swfupload.js?v=1.4.1"></script>
			<script type="text/javascript" src="http://ac.vipserv.org/madison_square2/design/_js_libs/json2.js?v=1.4.1"></script>
			<script type="text/javascript" src="http://ac.vipserv.org/madison_square2/design/_js_libs/base64.js?v=1.4.1"></script>
			<script type="text/javascript" src="http://ac.vipserv.org/madison_square2/design/_js_libs/jquery.onkeyup.js?v=1.4.1"></script>
			<script type="text/javascript" src="http://ac.vipserv.org/madison_square2/design/_js_libs/daterangepicker/js/daterangepicker.jQuery.js?v=1.4.1"></script>
			<script type="text/javascript" src="http://ac.vipserv.org/madison_square2/design/_js_panel/core/gekosale.js?v=1.4.1"></script>
			<script type="text/javascript">
				<?php echo '
					/*<![CDATA[*/
						new GCore({
							iCookieLifetime: 30,
							sDesignPath: \''; ?>
http://ac.vipserv.org/madison_square2/design/<?php echo '\',
							iActiveView: \''; ?>
<?php echo $this->_tpl_vars['view']; ?>
<?php echo '\',
							aoViews: '; ?>
<?php echo $this->_tpl_vars['views']; ?>
<?php echo ',
							iActiveLanguage: \''; ?>
<?php echo $this->_tpl_vars['language']; ?>
<?php echo '\',
							aoLanguages: '; ?>
<?php echo $this->_tpl_vars['languages']; ?>
<?php echo ',
							sUrl: \''; ?>
<?php echo $this->_tpl_vars['URL']; ?>
<?php echo '\',
							sCurrentController: \''; ?>
<?php echo $this->_tpl_vars['CURRENT_CONTROLLER']; ?>
<?php echo '\',
							sCurrentAction: \''; ?>
<?php echo $this->_tpl_vars['CURRENT_ACTION']; ?>
<?php echo '\',
						});
						$(document).ready(function(){
							$(\'#search\').GSearch(); 
						});
					/*]]>*/
				'; ?>

			</script>
			<script type="text/javascript" src="http://ac.vipserv.org/madison_square2/design/_js_panel/core/init.js"></script>
			<script type="text/javascript" src="http://ac.vipserv.org/madison_square2/design/_js_panel/core/gf.js"></script>
			<script type="text/javascript" src="http://ac.vipserv.org/madison_square2/design/_js_panel/core/pl_PL.js"></script>
			
			<script type="text/javascript">
				GF_Debug.s_iLevel = GF_Debug.LEVEL_ALL;
			</script>
			
		<!-- end: GexoFramework -->
		
		<?php echo $this->_tpl_vars['xajax']; ?>

		
		<?php if (isset ( $this->_tpl_vars['error'] )): ?>
			<script type="text/javascript">
				<?php echo '
					$(document).ready(function(){
						GError(\''; ?>
Wystąpił błąd<?php echo '\', \''; ?>
<?php echo $this->_tpl_vars['error']; ?>
<?php echo '\');
					});
				'; ?>

			</script>
		<?php endif; ?>
	</head>
	<body>

		<!-- begin: Header -->
			<div id="header">

				<div class="layout-container">

					<h1><a href="<?php echo $this->_tpl_vars['URL']; ?>
mainside" accesskey="0" title="Wróć do Pulpitu"><img src="data:image/png;base64,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" /></a></h1> 

						<div id="appversion">
							<h3>Wersja: <?php echo $this->_tpl_vars['appversion']; ?>
</h3>
						</div>
					<!-- begin: Quick Access -->
						<div id="quick-access">
							<h3>Szybki dostęp:</h3>
							<ul>
								<li><a href="<?php echo $this->_tpl_vars['URL']; ?>
products/add">Dodaj produkt</a></li>
							</ul>
						</div>

						<div id="livesearch">
							<h3>Szukaj: <input type="text" name="search" id="search" /></h3>
						</div>
						
						<script type="text/javascript">
							<?php echo '
								/*<![CDATA[*/
									var aoQuickAccessPossibilites = [
										{mLink: \''; ?>
<?php echo $this->_tpl_vars['URL']; ?>
<?php echo 'order\', sCaption: \''; ?>
Lista zamówień<?php echo '\', bDefault: true},
										{mLink: \''; ?>
<?php echo $this->_tpl_vars['URL']; ?>
<?php echo 'product/add\', sCaption: \''; ?>
Dodaj produkt<?php echo '\', bDefault: true},
										{mLink: \''; ?>
<?php echo $this->_tpl_vars['URL']; ?>
<?php echo 'product\', sCaption: \''; ?>
Lista produktów<?php echo '\', bDefault: true},
										{mLink: \''; ?>
<?php echo $this->_tpl_vars['URL']; ?>
<?php echo 'category/add\', sCaption: \''; ?>
Dodaj kategorię<?php echo '\'},
										{mLink: \''; ?>
<?php echo $this->_tpl_vars['URL']; ?>
<?php echo 'client/add\', sCaption: \''; ?>
Dodaj klienta<?php echo '\'},
										{mLink: \''; ?>
<?php echo $this->_tpl_vars['URL']; ?>
<?php echo 'category\', sCaption: \''; ?>
Lista kategorii<?php echo '\'},
										{mLink: \''; ?>
<?php echo $this->_tpl_vars['URL']; ?>
<?php echo 'client\', sCaption: \''; ?>
Lista klientów<?php echo '\'},
										{mLink: \''; ?>
<?php echo $this->_tpl_vars['URL']; ?>
<?php echo 'statssales\', sCaption: \''; ?>
Statystyki sprzedaży<?php echo '\', bDefault: true},
										{mLink: \''; ?>
<?php echo $this->_tpl_vars['URL']; ?>
<?php echo 'statsclients\', sCaption: \''; ?>
Statystyki klientów<?php echo '\', bDefault: true},
										{mLink: \''; ?>
<?php echo $this->_tpl_vars['URL']; ?>
<?php echo 'statsproducts\', sCaption: \''; ?>
Statystyki produktów<?php echo '\', bDefault: true},
										{mLink: \''; ?>
<?php echo $this->_tpl_vars['URL']; ?>
<?php echo 'productpromotion\', sCaption: \''; ?>
Lista promocji<?php echo '\'}
									];
								/*]]>*/
							'; ?>

						</script>
						<div id="top-menu">
							<ul>
								<li>
									<a href="<?php echo $this->_tpl_vars['URL']; ?>
users/edit/<?php echo $this->_tpl_vars['user_id']; ?>
" class="icon-person"><strong><?php echo $this->_tpl_vars['user_name']; ?>
</strong></a>
								</li>
								<li>
									<a href="<?php echo $this->_tpl_vars['URL']; ?>
logout;" class="icon-logout">Wyloguj</a>
								</li>
								<li>
									<a href="<?php echo $this->_tpl_vars['FRONTEND_URL']; ?>
" target="_blank" >Strona główna sklepu</a>
								</li>
							</ul>
						</div>
				</div>
			</div>
			<div id="navigation-bar">
				<div class="layout-container">
				
					<div id="selectors" style="float: right; margin-top: 8px;"></div>
					
					<ul id="navigation">
						<?php if (isset ( $this->_tpl_vars['menu'] )): ?>
							<?php unset($this->_sections['block']);
$this->_sections['block']['name'] = 'block';
$this->_sections['block']['loop'] = is_array($_loop=$this->_tpl_vars['menu']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['block']['show'] = true;
$this->_sections['block']['max'] = $this->_sections['block']['loop'];
$this->_sections['block']['step'] = 1;
$this->_sections['block']['start'] = $this->_sections['block']['step'] > 0 ? 0 : $this->_sections['block']['loop']-1;
if ($this->_sections['block']['show']) {
    $this->_sections['block']['total'] = $this->_sections['block']['loop'];
    if ($this->_sections['block']['total'] == 0)
        $this->_sections['block']['show'] = false;
} else
    $this->_sections['block']['total'] = 0;
if ($this->_sections['block']['show']):

            for ($this->_sections['block']['index'] = $this->_sections['block']['start'], $this->_sections['block']['iteration'] = 1;
                 $this->_sections['block']['iteration'] <= $this->_sections['block']['total'];
                 $this->_sections['block']['index'] += $this->_sections['block']['step'], $this->_sections['block']['iteration']++):
$this->_sections['block']['rownum'] = $this->_sections['block']['iteration'];
$this->_sections['block']['index_prev'] = $this->_sections['block']['index'] - $this->_sections['block']['step'];
$this->_sections['block']['index_next'] = $this->_sections['block']['index'] + $this->_sections['block']['step'];
$this->_sections['block']['first']      = ($this->_sections['block']['iteration'] == 1);
$this->_sections['block']['last']       = ($this->_sections['block']['iteration'] == $this->_sections['block']['total']);
?>
								<li>
									<?php if (isset ( $this->_tpl_vars['menu'][$this->_sections['block']['index']]['elements'] )): ?>
										<a href="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo $this->_tpl_vars['menu'][$this->_sections['block']['index']]['elements'][0]['link']; ?>
"><?php echo $this->_tpl_vars['menu'][$this->_sections['block']['index']]['name']; ?>
</a>
									<?php else: ?>
										<?php if ($this->_tpl_vars['menu'][$this->_sections['block']['index']]['link'] == 'mainside'): ?>
											<a href="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo $this->_tpl_vars['menu'][$this->_sections['block']['index']]['link']; ?>
"><?php echo $this->_tpl_vars['menu'][$this->_sections['block']['index']]['name']; ?>
</a>
										<?php endif; ?>
									<?php endif; ?>
									<?php if (isset ( $this->_tpl_vars['menu'][$this->_sections['block']['index']]['elements'] )): ?>
										<ul>
											<?php unset($this->_sections['element']);
$this->_sections['element']['name'] = 'element';
$this->_sections['element']['loop'] = is_array($_loop=$this->_tpl_vars['menu'][$this->_sections['block']['index']]['elements']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['element']['show'] = true;
$this->_sections['element']['max'] = $this->_sections['element']['loop'];
$this->_sections['element']['step'] = 1;
$this->_sections['element']['start'] = $this->_sections['element']['step'] > 0 ? 0 : $this->_sections['element']['loop']-1;
if ($this->_sections['element']['show']) {
    $this->_sections['element']['total'] = $this->_sections['element']['loop'];
    if ($this->_sections['element']['total'] == 0)
        $this->_sections['element']['show'] = false;
} else
    $this->_sections['element']['total'] = 0;
if ($this->_sections['element']['show']):

            for ($this->_sections['element']['index'] = $this->_sections['element']['start'], $this->_sections['element']['iteration'] = 1;
                 $this->_sections['element']['iteration'] <= $this->_sections['element']['total'];
                 $this->_sections['element']['index'] += $this->_sections['element']['step'], $this->_sections['element']['iteration']++):
$this->_sections['element']['rownum'] = $this->_sections['element']['iteration'];
$this->_sections['element']['index_prev'] = $this->_sections['element']['index'] - $this->_sections['element']['step'];
$this->_sections['element']['index_next'] = $this->_sections['element']['index'] + $this->_sections['element']['step'];
$this->_sections['element']['first']      = ($this->_sections['element']['iteration'] == 1);
$this->_sections['element']['last']       = ($this->_sections['element']['iteration'] == $this->_sections['element']['total']);
?>
												<li <?php if ($this->_tpl_vars['CURRENT_CONTROLLER'] == $this->_tpl_vars['menu'][$this->_sections['block']['index']]['elements'][$this->_sections['element']['index']]['link']): ?>class="active"<?php endif; ?>>
													<a href="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo $this->_tpl_vars['menu'][$this->_sections['block']['index']]['elements'][$this->_sections['element']['index']]['link']; ?>
"><?php echo $this->_tpl_vars['menu'][$this->_sections['block']['index']]['elements'][$this->_sections['element']['index']]['name']; ?>
</a>
													<?php if (isset ( $this->_tpl_vars['menu'][$this->_sections['block']['index']]['elements'][$this->_sections['element']['index']]['subelement'] )): ?>
														<ul>
															<?php unset($this->_sections['sub']);
$this->_sections['sub']['name'] = 'sub';
$this->_sections['sub']['loop'] = is_array($_loop=$this->_tpl_vars['menu'][$this->_sections['block']['index']]['elements'][$this->_sections['element']['index']]['subelement']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['sub']['show'] = true;
$this->_sections['sub']['max'] = $this->_sections['sub']['loop'];
$this->_sections['sub']['step'] = 1;
$this->_sections['sub']['start'] = $this->_sections['sub']['step'] > 0 ? 0 : $this->_sections['sub']['loop']-1;
if ($this->_sections['sub']['show']) {
    $this->_sections['sub']['total'] = $this->_sections['sub']['loop'];
    if ($this->_sections['sub']['total'] == 0)
        $this->_sections['sub']['show'] = false;
} else
    $this->_sections['sub']['total'] = 0;
if ($this->_sections['sub']['show']):

            for ($this->_sections['sub']['index'] = $this->_sections['sub']['start'], $this->_sections['sub']['iteration'] = 1;
                 $this->_sections['sub']['iteration'] <= $this->_sections['sub']['total'];
                 $this->_sections['sub']['index'] += $this->_sections['sub']['step'], $this->_sections['sub']['iteration']++):
$this->_sections['sub']['rownum'] = $this->_sections['sub']['iteration'];
$this->_sections['sub']['index_prev'] = $this->_sections['sub']['index'] - $this->_sections['sub']['step'];
$this->_sections['sub']['index_next'] = $this->_sections['sub']['index'] + $this->_sections['sub']['step'];
$this->_sections['sub']['first']      = ($this->_sections['sub']['iteration'] == 1);
$this->_sections['sub']['last']       = ($this->_sections['sub']['iteration'] == $this->_sections['sub']['total']);
?>
																<li <?php if ($this->_tpl_vars['CURRENT_CONTROLLER'] == $this->_tpl_vars['menu'][$this->_sections['block']['index']]['elements'][$this->_sections['element']['index']]['subelement'][$this->_sections['sub']['index']]['link']): ?>class="active"<?php endif; ?>>
																	<a href="<?php echo $this->_tpl_vars['URL']; ?>
<?php echo $this->_tpl_vars['menu'][$this->_sections['block']['index']]['elements'][$this->_sections['element']['index']]['subelement'][$this->_sections['sub']['index']]['link']; ?>
"><?php echo $this->_tpl_vars['menu'][$this->_sections['block']['index']]['elements'][$this->_sections['element']['index']]['subelement'][$this->_sections['sub']['index']]['name']; ?>
</a>
																</li>	
															<?php endfor; endif; ?>
														</ul>
													<?php endif; ?>
												</li>	
											<?php endfor; endif; ?>
										</ul>
									<?php endif; ?>
								</li>	
							<?php endfor; endif; ?>
						<?php endif; ?>
					</ul>
				</div>
			</div>
			<div id="message-bar">
				<h2 class="aural">Aktualności</h2>
			</div>
			<div id="content" class="layout-container">
		