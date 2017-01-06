@echo off

set hostedRoot=%HOMEDRIVE%%HOMEPATH%\.hosted

mkdir "%hostedRoot%"

copy /-y src\stubs\Hosted.yaml "%hostedRoot%\Hosted.yaml"
copy /-y src\stubs\after.sh "%hostedRoot%\after.sh"
copy /-y src\stubs\aliases "%hostedRoot%\aliases"

set hostedRoot=
echo Hosted initialized!
