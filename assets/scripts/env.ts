import SymfonyRoutes            from "./core/symfony/SymfonyRoutes";
import DotenvIsDemoResponseDto  from "./core/dto/DotenvIsDemoResponseDto";
let axios = require('axios');
let VUE_APP_DEFAULT_STRING_BEFORE_TRANSLATING = "...";

/**
 * @description return information if the demo mode is on
 */
async function isDemo(): Promise<boolean>
{
    return axios.get(SymfonyRoutes.ENV_IS_DEMO).then( (response) => {
        let isDemoResponseDto = DotenvIsDemoResponseDto.fromAxiosResponse(response);
        return isDemoResponseDto.isDemo;
    })
}

export {VUE_APP_DEFAULT_STRING_BEFORE_TRANSLATING, isDemo};