#ifndef AOC2018_INPUT_H
#define AOC2018_INPUT_H

#include <list>

class Input {
    private:
        std::string fileName;
        std::list<int> content;
    public:
        Input(const std::string& fileName);
        std::list<int> getContent();
    };
#endif //AOC2018_INPUT_H
