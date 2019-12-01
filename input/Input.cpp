#include <iostream>
#include <fstream>
#include "Input.h"

using namespace std;

Input::Input(const std::string& fileName) {
    ifstream inFile;
    int rowContent;

    inFile.open(fileName);
    if(inFile.fail())
    {
        cout << "Failed to open file!" << endl;
    }
    while (inFile >> rowContent)
    {
        this->content.push_back(rowContent);
    }

    inFile.close();
}

std::list<int> Input::getContent() {
    return this->content;
}
