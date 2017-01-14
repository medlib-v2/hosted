@echo off

set hostedRoot=%HOMEDRIVE%%HOMEPATH%\.hosted

mkdir "%hostedRoot%"

copy /-y command\src\stubs\Hosted.yaml "%hostedRoot%\Hosted.yaml"
copy /-y command\src\stubs\after.sh "%hostedRoot%\after.sh"
copy /-y command\src\stubs\aliases "%hostedRoot%\aliases"

set hostedRoot=
echo Hosted initialized!
