#!/usr/bin/env bash

hostedRoot=~/.hosted

mkdir -p "$hostedRoot"

cp -i src/stubs/Hosted.yaml "$hostedRoot/Hosted.yaml"
cp -i src/stubs/after.sh "$hostedRoot/after.sh"
cp -i src/stubs/aliases "$hostedRoot/aliases"

echo "Hosted initialized!"
