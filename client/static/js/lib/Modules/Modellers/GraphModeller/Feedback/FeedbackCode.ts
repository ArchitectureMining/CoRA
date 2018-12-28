/// <reference path='./index.ts'/>

enum FeedbackCode {
    // initial state
    NO_INITIAL_STATE = 400,
    INCORRECT_INITIAL_STATE = 401,
    CORRECT_INITIAL_STATE = 200,

    // states
    REACHABLE_FROM_PRESET = 220,
    DUPLICATE_STATE = 320,    
    NOT_REACHABLE_FROM_PRESET = 420,
    EDGE_MISSING = 421,
    NOT_REACHABLE_FROM_INITIAL = 422,

    // edges
    ENABLED_CORRECT_POST = 240,
    ENABLED_CORRECT_POST_WRONG_LABEL = 440,
    ENABLED_INCORRECT_POST = 441,
    DISABLED = 442,
    DISABLED_CORRECT_POST = 443,
    DUPLICATE_EDGE = 340,
}