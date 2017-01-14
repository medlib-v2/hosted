#!/usr/bin/env bash

hostedRoot=~/.hosted

mkdir -p "$hostedRoot"

cp -i command/src/stubs/Hosted.yaml "$hostedRoot/Hosted.yaml"
cp -i command/src/stubs/after.sh "$hostedRoot/after.sh"
cp -i command/src/stubs/aliases "$hostedRoot/aliases"

echo "Hosted initialized!"
