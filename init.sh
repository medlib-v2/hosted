#!/usr/bin/env bash

if [[ -n "$1" ]]; then
    cp -i command/resources/Settings.json Settings.json
else
    cp -i command/resources/Settings.yaml Settings.yaml
fi

cp -i command/resources/after.sh after.sh
cp -i command/resources/aliases aliases

echo "Hosted Box initialized!"
