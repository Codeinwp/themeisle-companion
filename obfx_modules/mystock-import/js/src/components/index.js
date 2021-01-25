import PhotoList from "./PhotoList";
import SetFeaturedImage from "./SetFeaturedImage";
import InsertImage from "./InsertImage";

const { Fragment } = wp.element;
const { PluginSidebar, PluginSidebarMoreMenuItem } = wp.editPost;
const { __ } = wp.i18n;

const Mystock = () => {
	return (
		<Fragment>
			<PluginSidebarMoreMenuItem
				target ="mystock-sidebar"
			>
				{ __( 'MyStockPhotos', 'themeisle-companion' ) }
			</PluginSidebarMoreMenuItem>
			<PluginSidebar
				name="mystock-sidebar"
				title={ __( 'MyStockPhotos', 'themeisle-companion' ) }
			>
				<div className="mystock-img-container">
					<PhotoList page={1} SetFeaturedImage={SetFeaturedImage} InsertImage={InsertImage} />
				</div>
			</PluginSidebar>
		</Fragment>
	);
};
export default Mystock;
