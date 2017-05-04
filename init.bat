@echo off

if ["%~1"]==["json"] (
    copy /-y command\resources\Settings.json Settings.json
)
if ["%~1"]==[""] (
    copy /-y command\resources\Settings.yaml Settings.yaml
)

copy /-y command\resources\after.sh after.sh
copy /-y command\resources\aliases aliases

echo Hosted Box initialized!