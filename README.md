# ramverk1-module Frida Lindgren

[![Build status](https://travis-ci.org/fridalindgren91/ramverk1-module.svg?branch=master)](https://travis-ci.org/fridalindgren91/ramverk1-module)

Detta är en modul som kan installeras i ramverket Anax för att få tillgång till Ip validering samt väderprognos.

## Installering

För att installera modulen, kör följande kommando:  
    composer require fridalindgren91/ramverk1-module 

För att kopiera över config filer och vyer, kör följande kommandon:  
    rsync -av vendor/fridalindgren91/ramverk1-module/config/ config/  
    rsync -av vendor/fridalindgren91/ramverk1-module/view/ view/
