cmake_minimum_required(VERSION 3.15)
project(aoc2019)

set(CMAKE_CXX_STANDARD 14)

include_directories(.)
include_directories(./input)

add_executable(aoc2019
        README.md 01.cpp
        class/Input.h class/Input.cpp)
