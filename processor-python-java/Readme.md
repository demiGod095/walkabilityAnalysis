This folder contains the data that gets processed on the processor node and the code that porcesses it.

`preprocess` uploads the data to the couchdb server
 - before executing `upload.py`, you need to execute the extract script `bash run_extract-psma` in the features folder.

`walk-tool_projected-data` has the files necessary for running the walkability tool to calculate walkability of either inner melbourne or greater melbourne.
 - execute the extract script `bash run_extract-shp.sh`
 - Then, run either `bash run_melb-greater.sh` or `bash run_melb-inner.sh` to execute the jar with the proper parameters.
