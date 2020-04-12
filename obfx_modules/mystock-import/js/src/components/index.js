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
				icon = "camera"
				target ="mystock-sidebar"
			>
				{ __( 'MyStockPhotos', 'themeisle-companion' ) }
			</PluginSidebarMoreMenuItem>
			<PluginSidebar
				icon="camera"
				name="mystock-sidebar"
				title={ __( 'MyStockPhotos', 'themeisle-companion' ) }
			>
				<div className="mystock-img-container">
					<PhotoList page='1' SetFeaturedImage={SetFeaturedImage} InsertImage={InsertImage} />
				</div>
			</PluginSidebar>
		</Fragment>
	);
};
export default Mystock;
