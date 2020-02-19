# ramverk1-module Frida Lindgren

[![Build status](https://travis-ci.org/fridalindgren91/ramverk1-module.svg?branch=master)](https://travis-ci.org/fridalindgren91/ramverk1-module)

[![CircleCI](https://circleci.com/gh/fridalindgren91/ramverk1-module.svg?style=svg)](https://circleci.com/gh/fridalindgren91/ramverk1-module)

[![Build Status](https://scrutinizer-ci.com/g/fridalindgren91/ramverk1-module/badges/build.png?b=master)](https://scrutinizer-ci.com/g/fridalindgren91/ramverk1-module/build-status/master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/fridalindgren91/ramverk1-module/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/fridalindgren91/ramverk1-module/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/fridalindgren91/ramverk1-module/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/fridalindgren91/ramverk1-module/?branch=master)
[[![Code Intelligence Status](https://scrutinizer-ci.com/g/fridalindgren91/ramverk1-module/badges/code-intelligence.svg?b=master)](https://scrutinizer-ci.com/code-intelligence)]

Detta är en modul som kan installeras i ramverket Anax för att få tillgång till Ip validering samt väderprognos.

## Installering

För att installera modulen, kör följande kommando:  
    composer require fridalindgren91/ramverk1-module 

För att kopiera över config filer och vyer, kör följande kommandon:  
    rsync -av vendor/fridalindgren91/ramverk1-module/config/ config/  
    rsync -av vendor/fridalindgren91/ramverk1-module/view/ view/
