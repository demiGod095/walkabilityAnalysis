This is a modified version of the eclipse project found [here](https://github.com/Serena-Chenzz/WalkabilityAurin/tree/master/Walkability-5points/src/main/java/org/mccaughey)

To make changes, simply import this folder into your Java Eclipse workspace and let the maven dependancies to get resolved.
You might have to fix any java jdk version matching errors by configuring the project's build path. The easiest way to do this is to disable project specific java dependancy.
The original project was using `Java 1.7`, but the current source is updated to use `Java 1.8`

In order to run the code, you will have to go to `Run Configurations` and add arguments as specified by the usage function.
Main method to use is in the `MainJarOMS` class to run the walkability program.

Logging level is set to `OFF` by default, but you can change it in the `log4j.properties` file.

When exporting as jar, the log4j.properties doesn't get added to the jar as it is a configuration file. It needs to be accessed via a commandline argument in the following manner when running the jar:-
  - `-Dlog4j.configuration=file:/<path_to>/log4j.properties`
